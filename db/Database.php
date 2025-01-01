<?php
require_once __DIR__ . '/../class/Personne.php';
require_once __DIR__ . '/../config/db.ini';



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
            die("Erreur de connexion. Veuillez réessayer plus tard.");
        }
    }

    //TOUT CE QUI CONCERNE LES LIVRES
    public function creerTableBookState(): bool
    {
        $sql = <<<COMMANDE_SQL
            CREATE TABLE book_state (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    book_state TEXT
            )
    COMMANDE_SQL;


        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }
    public function creerTableBook(): bool
    {
        $sql = <<<COMMANDE_SQL
        CREATE TABLE if not existsBook (
            id INT AUTO_INCREMENT PRIMARY KEY,
            Title VARCHAR(255),
            Author VARCHAR(255),
            Editor VARCHAR(255),
            Parution_date DATE,
            ISBN VARCHAR(20) UNIQUE
            );
    COMMANDE_SQL;


        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }
    //Créer table users
    public function creerTablelecture(): bool
    {
        $sql = <<<COMMANDE_SQL
        CREATE TABLE lecture (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    `actual page` INTEGER,
    `date_begin` TEXT, -- SQLite stocke les dates comme du texte (ISO8601)
    `date_end` TEXT, -- SQLite stocke les dates comme du texte (ISO8601)
    book_state_id INTEGER,
    book_id INTEGER,
    user_id INTEGER,
    FOREIGN KEY (book_state_id) REFERENCES book_state(id),
    FOREIGN KEY (book_id) REFERENCES Book(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
        );
COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }
    public function creerTablecomment(): bool
    {
        $sql = <<<COMMANDE_SQL
      CREATE TABLE comment (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    book_id INTEGER,
    user_id INTEGER,
    content TEXT,
    date TEXT, -- SQLite stocke les dates comme du texte (ISO8601)
    FOREIGN KEY (book_id) REFERENCES Book(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
        );
COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }
    public function creerTablegrade(): bool
    {
        $sql = <<<COMMANDE_SQL
     CREATE TABLE grade (
    id INTEGER PRIMARY KEY AUTOINCREMENT,
    book_id INTEGER,
    user_id INTEGER,
    grade INTEGER,
    FOREIGN KEY (book_id) REFERENCES Book(id),
    FOREIGN KEY (user_id) REFERENCES user(id)
        );
COMMANDE_SQL;

        try {
            $this->db->exec($sql);
            $ok = true;
        } catch (PDOException $e) {
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }

    //TABLE USERS
    public function creerTableusers(): bool
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
            $e->getMessage();
            $ok = false;
        }
        return $ok;
    }
    public function initialistion(): bool
    {
        $this->creerTableBookState();
        $this->creerTableBook();
        $this->creerTableusers();
        $this->creerTablelecture();
        $this->creerTablecomment();
        $this->creerTablegrade();

        return true;
    }

    //Méthodes users
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

    public function confirmeInscription($id)
{
    $sql = "UPDATE users SET is_confirmed = 1 WHERE id = :id";
    $stmt = $this->db->prepare($sql);
    $stmt->bindValue(':id', $id, \PDO::PARAM_INT);
    return $stmt->execute();
}
}
