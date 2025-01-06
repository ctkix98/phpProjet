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
                );
                $this->addBook($bookObject);
            }
        } catch (Exception $e) {
            error_log("Error: " . $e->getMessage());
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

    public function addBook(Book $book): bool
    {
        try {
            $ok = true;
            $sql = "INSERT INTO Book (Title, Author, Theme, Parution_date, ISBN, cover_image_path)
                    VALUES (:title, :author, :theme, :parution_date, :isbn, :cover_image_path)";
            $stmt = $this->db->prepare($sql);

            // Lier les paramètres
            $stmt->bindParam(':title', $book->title);
            $stmt->bindParam(':author', $book->author);
            $stmt->bindParam(':theme', $book->theme);
            $stmt->bindParam(':parution_date', $book->parution_date);

            // Gestion de l'ISBN
            if ($book->isbn === 'NULL' || empty($book->isbn)) {
                $stmt->bindValue(':isbn', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':isbn', $book->isbn);
            }

            // Gestion du chemin de l'image de couverture
            if (empty($book->cover_image_path)) {
                $stmt->bindValue(':cover_image_path', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':cover_image_path', $book->cover_image_path);
            }

            // Exécuter la requête
            $ok = $ok && $stmt->execute();
            echo $book->title . " ajouté avec succès.";
            echo "<br>";
        } catch (\PDOException $e) {
            echo "Erreur lors de l'ajout du livre '{$book->title}': " . $e->getMessage();
        }
        return $ok;
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

            // Lier les paramètres de la requête avec les propriétés du livre
            $stmt->bindParam(':title', $book->title);
            $stmt->bindParam(':author', $book->author);
            $stmt->bindParam(':theme', $book->theme);
            $stmt->bindParam(':parution_date', $book->parution_date);

            // Définir le statut de validation à "pending"
            $validationStatus = 'pending';  // Déclaration de la variable ici
            $stmt->bindParam(':validation_status', $validationStatus);  // Puis lier la variable

            // Vérifier si l'ISBN est fourni
            if ($book->isbn === 'NULL' || empty($book->isbn)) {
                $stmt->bindValue(':isbn', null, PDO::PARAM_NULL);
            } else {
                $stmt->bindParam(':isbn', $book->isbn);
            }

            // Exécuter la requête pour insérer le livre avec son statut de validation
            $ok = $ok && $stmt->execute();

            // Vérifier si l'exécution a réussi
            if ($ok) {
                echo $book->title . " ajouté et soumis à validation.";
                echo "<br>";
            }
        } catch (\PDOException $e) {
            // En cas d'erreur, afficher le message d'erreur
            echo "Erreur lors de l'ajout du livre '{$book->title}': " . $e->getMessage();
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
                    $row['cover_image_url']
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

    function getBooksById($bookId) {
        $sql = "SELECT * FROM book WHERE id = :book_id";
        $stmt = $this->getDb()->prepare($sql);
        $stmt->bindParam(':book_id', $bookId, PDO::PARAM_INT);
        $stmt->execute();
        return $stmt->fetch(PDO::FETCH_ASSOC);
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
}
