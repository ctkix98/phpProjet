<?php
require_once __DIR__ . '/../class/Personne.php';
require_once __DIR__ . '/../class/Book.php';


class Database
{
    private $db;

    public function __construct()
    {
        $config = parse_ini_file(__DIR__ . '/../config/db.ini');
        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        //initialisation à la DB
        try {
            $this->db = new \PDO($dsn, $username, $password);
        } catch (PDOException $e) {
            error_log("Erreur de connexion : " . $e->getMessage());
            // die("Erreur de connexion. Veuillez réessayer plus tard.");
            die("Erreur de connexion : " . $e->getMessage());
        }
        $this->initialisation();
    }

    // Getter pour accéder à $db
    public function getDb()
    {
        return $this->db;
    }

    //TOUT CE QUI CONCERNE LES LIVRES
    public function createTableBookState(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS book_state (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                book_state TEXT
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTableBook(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS book (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                Title TEXT,
                Author TEXT,
                Theme TEXT,
                Parution_date TEXT, -- SQLite stores dates as text (ISO8601)
                ISBN TEXT UNIQUE,
                cover_image_path TEXT
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTableBookValidation(): bool
    {
        $sql = <<<COMMANDE_SQL
        CREATE TABLE IF NOT EXISTS book_validation (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            Title TEXT,
            Author TEXT,
            Theme TEXT,
            Parution_date TEXT, -- SQLite stores dates as text (ISO8601)
            ISBN TEXT UNIQUE,
            validation_status TEXT DEFAULT 'pending' -- Status: 'pending', 'approved', 'rejected'
        );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }


    public function createTablelecture(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS lecture (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                actual_page INTEGER,
                date_begin TEXT, -- SQLite stores dates as text (ISO8601)
                date_end TEXT, -- SQLite stores dates as text (ISO8601)
                book_state_id INTEGER,
                book_id INTEGER,
                user_id INTEGER,
                FOREIGN KEY (book_state_id) REFERENCES book_state(id),
                FOREIGN KEY (book_id) REFERENCES book(id),
                FOREIGN KEY (user_id) REFERENCES users(id),
                UNIQUE(book_id, user_id)
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTablecomment(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS comment (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                book_id INTEGER,
                user_id INTEGER,
                content TEXT,
                date TEXT, -- SQLite stores dates as text (ISO8601)
                FOREIGN KEY (book_id) REFERENCES book(id),
                FOREIGN KEY (user_id) REFERENCES users(id)
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTablegrade(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS grade (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                book_id INTEGER,
                user_id INTEGER,
                grade INTEGER,
                FOREIGN KEY (book_id) REFERENCES book(id),
                FOREIGN KEY (user_id) REFERENCES users(id)
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTableusers(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS users (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                pseudo VARCHAR(120) NOT NULL UNIQUE,
                email VARCHAR(120) NOT NULL UNIQUE,
                password VARCHAR(255) NOT NULL,
                token VARCHAR(255) DEFAULT NULL,
                is_confirmed BOOLEAN DEFAULT 0
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }

    public function createTableSettings(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE IF NOT EXISTS settings (
                id INTEGER PRIMARY KEY AUTOINCREMENT,
                key_name VARCHAR(255) UNIQUE NOT NULL, 
                key_value VARCHAR(255) NOT NULL
            );
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            $ok = false;
        }
        return $ok;
    }
    public function initialisation(): bool
    {
        $ok = true;
        $ok = $ok && $this->createTableBookState();
        $ok = $ok && $this->createTableBook();
        $ok = $ok && $this->createTableusers();
        $ok = $ok && $this->createTablelecture();
        $ok = $ok && $this->createTablecomment();
        $ok = $ok && $this->createTablegrade();
        $ok = $ok && $this->createTableSettings();
        $ok = $ok && $this->createTableBookValidation();
        $ok = $ok && $this->insertBooks();

        return $ok;
    }

    //Méthodes pour récupérer / vérifier table Personne
    public function ajouterPersonne(Personne $personne): int
    {
        $datas = [
            'pseudo' => $personne->rendPseudo(),
            'email' => $personne->rendEmail(),
            'password' => $personne->rendpassword(),
            'token' => $personne->rendToken(),
        ];

        // Appeler la méthode recupereContact avec le numéro de téléphone et email
        if (!$this->recupererContact($datas['pseudo'], $datas['email'])) {
            $sql = "INSERT INTO users (pseudo, email, password, token) VALUES "
                . "(:pseudo, :email, :password, :token)";
            $stmt = $this->db->prepare($sql);

            // Exécutez la requête et gérez les erreurs
            try {
                $stmt->execute($datas);
                return $this->db->lastInsertId();
            } catch (PDOException $e) {
                echo "Erreur lors de l'insertion : " . $e->getMessage();
                return 0;
            }
        } else {
            echo "Le pseudo ou l'email existe déjà";
            echo "<br>";
            return 0; // Retourner une valeur pour indiquer l'échec
        }
    }
    public function getUserByToken($token)
    {
        $sql = "SELECT * FROM users WHERE token = :token";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':token', $token);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    public function getUserById($id)
    {
        $sql = "SELECT * FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }


    public function verifierConnection(string $pseudo, string $password): bool
    {
        $retour = false;
        if (empty($pseudo) or empty($password)) {
            echo ("Il faut un mot de passe ET un pseudo");
        }
        if (!$this->recupererContact($pseudo, $password)) {
            $retour = true;
        }
        return $retour;
    }

    public function recupererContact(string $pseudo, string $email): bool
    {
        $sql = "SELECT * FROM users WHERE pseudo = :pseudo OR email = :email";
        $stmt = $this->db->prepare($sql);

        // Lier les paramètres
        $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
        $stmt->bindParam(':email', $email, \PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Vérifier si des résultats ont été trouvés
        return $stmt->fetch(\PDO::FETCH_ASSOC) !== false;
    }

    //Fonction pour vérifier password et pseudo
    public function verifierAccesEtRecupererUtilisateur(string $pseudo): ?array
    {
        // Prépare la requête pour récupérer les données de l'utilisateur, y compris l'id
        $sql = "SELECT id, pseudo, password, email FROM users WHERE pseudo = :pseudo";
        $stmt = $this->db->prepare($sql);

        // Lier l'adresse e-mail
        $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);

        // Exécuter la requête
        $stmt->execute();

        // Récupérer les résultats
        $utilisateur = $stmt->fetch(\PDO::FETCH_ASSOC);

        // Retourne toutes les données de l'utilisateur, y compris l'id
        return $utilisateur ? $utilisateur : null; // Si l'utilisateur existe, retourne ses données, sinon null
    }

    //Méthodes pour update table Personne
    public function confirmeInscription($id)
    {
        $sql = "UPDATE users SET is_confirmed = 1 WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function updatePassword($id, $newPassword)
    {
        $sql = "UPDATE users SET password = :password WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':password', $newPassword, \PDO::PARAM_STR);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }
    public function deleteUser($id)
    {
        $sql = "DELETE FROM users WHERE id = :id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':id', $id, \PDO::PARAM_INT);
        return $stmt->execute();
    }


    //Méthodes pour les livres
    public function fetchTopBooksFromOpenLibrary($url): void
    {
        echo "Loading top books from";
        try {
            // Récupérer les données de l'URL
            if (!file_exists($url)) {
                throw new Exception("File not found: $url");
            }
            $response = file_get_contents($url);
            if ($response === false) {
                throw new Exception("Failed to fetch data from URL: $url");
            }

            // Décoder le JSON
            $books = json_decode($response, true);
            if (json_last_error() !== JSON_ERROR_NONE) {
                throw new Exception("Failed to decode JSON: " . json_last_error_msg());
            }

            // Afficher les données pour débogage

            // Exemple d'extraction des livres
            foreach ($books['works'] as $book) {
                $bookObject = new Book(
                    $book['title'],
                    $book['authors'][0]['name'] ?? 'Unknown',
                    $book['subject'][0] ?? 'Unknown',
                    $book['first_publish_year'] ?? 'Unknown',
                    $book['availability']['isbn'] ?? 'NULL',
                    $book['availability']['openlibrary_work'] ?? 'NULL',
                    $book['id'] ?? 'NULL'
                );
                $this->addBook($bookObject);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
        }
    }
    // Dans Database.ph
    public function insertOrUpdateBook(Book $book): bool
    {
        // Récupérer les valeurs des getters et les stocker dans des variables
        $title = $book->getTitle();
        $author = $book->getAuthor();  // Utilise la méthode getWriter() pour l'auteur
        $genre = $book->getTheme();   // Utilise la méthode getEditor() pour le genre (ou sinon un champ plus adapté comme getTheme())
        $year = $book->getYear();
        $isbn = $book->getIsbn();
        $coverImage = $book->getCoverImagePath(); // Récupérer le chemin de l'image de couverture

        // Requête SQL avec ON CONFLICT pour SQLite (utilisation de ISBN comme clé unique)
        $query = "INSERT INTO book (Title, Author, Theme, Parution_date, ISBN, cover_image_path) 
                  VALUES (:title, :author, :genre, :year, :isbn, :cover_image)
                  ON CONFLICT(ISBN) DO UPDATE
                  SET Title = :title, Author = :author, Theme = :genre, Parution_date = :year, cover_image_path = :cover_image";

        // Préparer la requête
        $stmt = $this->db->prepare($query);

        // Vérification des valeurs avant de lier les paramètres
        if (!$stmt) {
            echo "Erreur de préparation de la requête SQL : " . implode(", ", $this->db->errorInfo());
            return false;
        }

        // Lier les paramètres de manière sécurisée
        $stmt->bindParam(':title', $title, PDO::PARAM_STR);
        $stmt->bindParam(':author', $author, PDO::PARAM_STR);
        $stmt->bindParam(':genre', $genre, PDO::PARAM_STR);
        $stmt->bindParam(':year', $year, PDO::PARAM_STR);  // Parution_date est en texte, donc on passe une chaîne
        $stmt->bindParam(':isbn', $isbn, PDO::PARAM_STR);
        $stmt->bindParam(':cover_image', $coverImage, PDO::PARAM_STR);

        // Essayer d'exécuter la requête
        try {
            return $stmt->execute();
        } catch (PDOException $e) {
            echo "Erreur lors de l'exécution de la requête : " . $e->getMessage();
            return false;
        }
    }




    public function insertBooks(): bool
    {
        try {
            // Commence une transaction
            $this->db->beginTransaction();

            // Créer une instance de la classe Book
            $books = [
                new Book("Daphné et le duc", "Anthony Julia Quinn", "Romance", "2021", "9782290254738", "/assets/images/covers/daphne_et_duc.jpeg",1),
                new Book("Le cycle de Dune, Tome 1", "Frank Herbert", "Fantasy", "2021", "9782266320542", "/assets/images/covers/cycle_de_dune_tome1.jpeg",2),
                new Book("Fascination", "Stephenie Meyer", "Romance", "2011", "9782013212113", "/assets/images/covers/fascination.jpeg",3),
                new Book("L'Alchimiste", "Paulo Coelho", "Romance", "2021", "9782290258064", "/assets/images/covers/alchimiste.jpeg",4),
                new Book("La panthère des neiges", "Sylvain Tesson", "Fantasy", "2021", "9782072936494", "/assets/images/covers/panthere_des_neiges.jpeg",5),
                new Book("Les fiancés de l'hiver", "Christelle Dabos", "Fantasy", "2024", "9782075215466", "/assets/images/covers/fiances_de_lhiver.jpeg",6),
                new Book("La vie secrète des écrivains", "Guillaume Musso", "Film", "2020", "9782253237631", "/assets/images/covers/vie_secrete_ecrivains.jpeg",7),
                new Book("Les secrets de la femme de ménage", "Freida McFadden", "Film", "2024", "9782290391198", "/assets/images/covers/secret_femme_menage.jpeg",8),
                new Book("Le bal des folles", "Victoria Mas", "Film", "2021", "9782253103622", "/assets/images/covers/bal_des_folles.jpeg",9),
                new Book("Le silence du rossignol", "Lian Hearn", "Fantasy", "2021", "9782072934902", "/assets/images/covers/silence_rossignol.jpeg",10),
                new Book("Le consentement", "Vanessa Springorg", "Science", "2021", "9782253101567", "/assets/images/covers/consentement.jpeg",11),
                new Book("Le mage du Kremlin", "Guiliano da Empoli", "Horror", "2024", "9782073003911", "/assets/images/covers/mage_du_kremlin.jpeg",12),
                new Book("Le Petit Prince", "Antoine de Saint-Exupéry", "Fantasy", "1999", "9782070408504", "/assets/images/covers/petit_prince.jpeg",13),
                new Book("Le messie de Dune", "Frank Herbert", "Fantasy", "2021", "9782221255728", "/assets/images/covers/messie_dune.jpeg",14),
                new Book("Les figurants", "Delphine de Vigan", "Science", "2024", "9782073083999", "/assets/images/covers/figurants.jpeg",15),
            ];

            foreach ($books as $book) {
                $this->insertOrUpdateBook($book);
            }

            // Si tout est ok, valide la transaction
            $this->db->commit();
            return true;
        } catch (PDOException $e) {
            // Si une erreur se produit, annule la transaction
            $this->db->rollBack();
            // Ajout d'un message d'erreur plus détaillé
            echo "Erreur lors de l'insertion des livres : " . $e->getMessage();
            return false;
        }
    }


    public function getAllBooks()
    {
        try {
            // Préparation de la requête pour récupérer tous les livres de la table "book"
            $sql = "SELECT * FROM book";
            $stmt = $this->getDb()->prepare($sql);

            // Exécution de la requête
            $stmt->execute();

            // Récupération des résultats sous forme de tableau associatif
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $books;
        } catch (PDOException $e) {
            // Gestion des erreurs : enregistrez l'erreur dans un journal ou affichez un message
            error_log("Erreur lors de la récupération des livres : " . $e->getMessage());
            return [];
        }
    }

    public function getRandomBooks()
    {
        try {
            // Préparation de la requête pour récupérer 10 livres de manière aléatoire
            $sql = "SELECT * FROM book ORDER BY RANDOM() LIMIT 10";
            $stmt = $this->getDb()->prepare($sql);

            // Exécution de la requête
            $stmt->execute();

            // Récupération des résultats sous forme de tableau associatif
            $books = $stmt->fetchAll(PDO::FETCH_ASSOC);

            return $books;
        } catch (PDOException $e) {
            // Gestion des erreurs : enregistrez l'erreur dans un journal ou affichez un message
            error_log("Erreur lors de la récupération des livres : " . $e->getMessage());
            return [];
        }
    }


    public function addBook(Book $book): bool
    {
        try {
            $ok = true;
            $sql = "INSERT INTO Book (Title, Author, Theme, Parution_date, ISBN, cover_image_path)
                    VALUES (:title, :author, :theme, :parution_date, :isbn, :cover_image_path)";
            $stmt = $this->db->prepare($sql);
            $bookTitle = $book->getTitle();
            $bookAuthor = $book->getAuthor();
            $bookTheme = $book->getTheme();
            $bookParution_date = $book->getyear();
            $bookISBN = $book->getISBN();
            $bookCover_image_path = $book->getCoverImagePath();

            // Lier les paramètres
            $stmt->bindParam(':title', $bookTitle, PDO::PARAM_STR);
            $stmt->bindParam(':author', $bookAuthor);
            $stmt->bindParam(':theme', $bookTheme);
            $stmt->bindParam(':parution_date', $bookParution_date);

            // Gestion de l'ISBN
            if ($book->getIsbn() === 'NULL' || empty($book->getISBN())) {
                $stmt->bindValue(':isbn', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':isbn', $bookISBN, PDO::PARAM_STR);
            }

            // Gestion du chemin de l'image de couverture
            if (empty($book->getCoverImagePath())) {
                $stmt->bindValue(':cover_image_path', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':cover_image_path', $bookCover_image_path);
            }

            // Exécuter la requête
            $ok = $ok && $stmt->execute();
            echo $book->getTitle() . " ajouté avec succès.";
            echo "<br>";
        } catch (\PDOException $e) {
            echo "Erreur lors de l'ajout du livre '{$book->getTitle()}': " . $e->getMessage();
        }
        return $ok;
    }

    public function deleteBook($bookId)
    {
        try {
            // Préparer la requête SQL pour supprimer le livre
            $sql = "DELETE FROM book WHERE id = :id";
            $stmt = $this->getDb()->prepare($sql);

            // Lier le paramètre :id à l'ID du livre
            $stmt->bindParam(':id', $bookId, PDO::PARAM_INT);

            // Exécuter la requête
            $stmt->execute();

            // Vérifier si une ligne a été supprimée
            if ($stmt->rowCount() > 0) {
                return true; // Succès
            } else {
                return false; // Le livre n'a pas été trouvé ou supprimé
            }
        } catch (PDOException $e) {
            // Enregistrer l'erreur
            error_log("Erreur lors de la suppression du livre : " . $e->getMessage());
            return false;
        }
    }



    public function addBookState($state)
    {
        // Vérifier si l'état du livre est déjà présent dans la base de données
        echo "Ajout de l'état du livre";
        $sql = "INSERT INTO book_state (book_state) VALUES ('$state')";
        $stmt = $this->db->prepare($sql);
        $stmt->execute();
        echo "Etat du livre ajouté";
    }

    public function addBookForValidation(Book $book): bool
    {
        try {
            // Initialisation de la variable $ok pour vérifier le succès de l'exécution
            $ok = true;

            // Préparer la requête d'insertion dans la table book_validation
            $sql = "INSERT INTO book_validation (Title, Author, Theme, Parution_date, ISBN, validation_status)
                    VALUES (:title, :author, :theme, :parution_date, :isbn, :validation_status)";

            $stmt = $this->db->prepare($sql);
            $bookTitle = $book->getTitle();
            $bookAuthor = $book->getAuthor();
            $bookTheme = $book->getTheme();
            $bookParution_date = $book->getyear();
            $bookISBN = $book->getISBN();

            // Lier les paramètres de la requête avec les propriétés du livre
            $stmt->bindParam(':title', $bookTitle, PDO::PARAM_STR);
            $stmt->bindParam(':author', $bookAuthor);
            $stmt->bindParam(':theme', $bookTheme);
            $stmt->bindParam(':parution_date', $bookParution_date);

            // Définir le statut de validation à "pending"
            $validationStatus = 'pending';  // Déclaration de la variable ici
            $stmt->bindParam(':validation_status', $validationStatus);  // Puis lier la variable

            // Vérifier si l'ISBN est fourni
            if ($book->getIsbn() === 'NULL' || empty($book->getISBN())) {
                $stmt->bindValue(':isbn', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':isbn', $bookISBN);
            }

            // Exécuter la requête pour insérer le livre avec son statut de validation
            $ok = $ok && $stmt->execute();

            // Vérifier si l'exécution a réussi
            if ($ok) {
                echo $book->getTitle() . " ajouté et soumis à validation.";
                echo "<br>";
            }
        } catch (\PDOException $e) {
            // En cas d'erreur, afficher le message d'erreur
            echo "Erreur lors de l'ajout du livre '{$book->gettitle()}': " . $e->getMessage();
            $ok = false;
        }

        // Retourner le statut d'exécution
        return $ok;
    }




    public function updateBookValidationStatus($bookId, $status): bool
    {
        // Liste des statuts valides
        $validStatuses = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Statut invalide : $status");
        }

        // Mettre à jour le statut en utilisant la colonne 'id'
        $sql = "UPDATE book_validation SET validation_status = :status WHERE id = :book_id";
        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);
        $stmt->bindParam(':book_id', $bookId, \PDO::PARAM_INT);

        try {
            $stmt->execute();
            return true;
        } catch (PDOException $e) {
            // Enregistrer l'erreur dans les logs
            error_log("Erreur lors de la mise à jour du statut de validation : " . $e->getMessage());
            return false;
        }
    }

    //stocker les informations sur les couvertures
    public function addCoverMetadataToBookTable(): bool
    {
        $sql = <<<COMMANDE_SQL
            ALTER TABLE book
            ADD COLUMN cover_image_url TEXT,
            ADD COLUMN cover_format TEXT,
            ADD COLUMN cover_resolution TEXT;
        COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            return true;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return false;
        }
    }

    public function getBooks(): array
    {
        $sql = "SELECT * FROM book";

        try {
            $stmt = $this->db->query($sql);
            $books = [];
            while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
                $books[] = new Book(
                    $row['Title'],
                    $row['Author'],
                    $row['Theme'],
                    $row['Parution_date'],
                    $row['ISBN'],
                    $row['cover_image_url'],
                    $row['id'],
                );
            }
            return $books;
        } catch (PDOException $e) {
            error_log($e->getMessage());
            return [];
        }
    }


    public function getBooksByValidationStatus($status): array
    {
        $validStatuses = ['pending', 'approved', 'rejected'];
        if (!in_array($status, $validStatuses)) {
            throw new InvalidArgumentException("Statut invalide : $status");
        }

        $sql = <<<SQL
        SELECT b.id, b.Title, b.Author, b.Theme, b.Parution_date, b.ISBN, bv.validation_status
        FROM book_validation bv
        INNER JOIN book b ON bv.book_id = b.id
        WHERE bv.validation_status = :status
    SQL;

        $stmt = $this->db->prepare($sql);
        $stmt->bindParam(':status', $status, \PDO::PARAM_STR);

        try {
            $stmt->execute();
            return $stmt->fetchAll(\PDO::FETCH_ASSOC);
        } catch (PDOException $e) {
            error_log("Erreur lors de la récupération des livres : " . $e->getMessage());
            return [];
        }
    }
    public function getBookIds()
    {
        try {
            $query = "SELECT id FROM book";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Récupérer les IDs dans un tableau
            $bookIds = $stmt->fetchAll(PDO::FETCH_COLUMN);

            return $bookIds;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des IDs des livres : " . $e->getMessage();
            return [];
        }
    }


    function getBooksByState($userId, $state)
    {
        $sql = "SELECT b.* FROM book b
                JOIN lecture l ON b.id = l.book_id
                WHERE l.user_id = :user_id AND l.book_state_id = :book_state_id";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(':user_id', $userId, PDO::PARAM_INT);
        $stmt->bindParam(':book_state_id', $state, PDO::PARAM_STR);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    public function query()
    {
        try {
            $query = "SELECT id, ISBN, Title, Author FROM book WHERE cover_image_path IS NULL";
            $stmt = $this->db->prepare($query);
            $stmt->execute();

            // Récupérer les IDs dans un tableau
            $bookIds = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo ("hello");
            return $bookIds;
        } catch (PDOException $e) {
            echo "Erreur lors de la récupération des IDs des livres : " . $e->getMessage();
            return [];
        }
    }

    public function searchBooks($researchedWord)
    {
        echo "Searching books";
        $query = "SELECT id FROM book WHERE Title LIKE :researchedWord OR Author LIKE :researchedWord";
        $researchedWord = '%' . $researchedWord . '%';
        $stmt = $this->db->prepare($query);
        $stmt->bindParam(':researchedWord', $researchedWord);
        $stmt->execute();
        return $stmt->fetchAll(PDO::FETCH_ASSOC);
    }

    function getBooksById($bookId)
    {
        $sql = "SELECT * FROM book WHERE id = :book_id";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
    }
}
