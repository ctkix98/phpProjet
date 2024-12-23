<?php
require_once __DIR__ . '/../class/Personne.php';
require_once __DIR__ . '/../config/db.ini';



class Database {
    private $db;
    
    public function __construct() {
        //$path = realpath(dirname(__DIR__) . '/db/dbpsw.sqlite');
        
        $config = parse_ini_file(__DIR__ . '/../config/db.ini');        
        $dsn = $config['dsn'];
        $username = $config['username'];
        $password = $config['password'];
        //initialisation à la DB
        $this->db = new \PDO($dsn, $username, $password); 
        if (!$this->db) { //ici permet de voir si la DB est bien connectée
            die("Problème de connexion à la base de données");
        }
    }
//Créer table Personnes
public function creerTablePersonnes(): bool {
    $sql = <<<COMMANDE_SQL
        CREATE TABLE IF NOT EXISTS personnes (
            id INTEGER PRIMARY KEY AUTOINCREMENT,
            pseudo VARCHAR(120) NOT NULL UNIQUE,
            email VARCHAR(120) NOT NULL UNIQUE,
            password VARCHAR(255) NOT NULL
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

public function ajouterPersonne(Personne $personne): int {
    $datas = [
        'pseudo' => $personne->rendPseudo(),
        'email' => $personne->rendEmail(),
        'password' => $personne->rendpassword(),
    ];

    // Appeler la méthode recupereContact avec le numéro de téléphone et email
    if (!$this->recupererContact($datas['pseudo'], $datas['email'])) {
        $sql = "INSERT INTO personnes (pseudo, email, password) VALUES "
                . "(:pseudo, :email, :password)";
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

public function verifierConnection(string $pseudo, string $password):bool{
    $retour = false;
    if(empty($pseudo)OR empty($password)){
        echo("Il faut un mot de passe ET un pseudo");
    }
    if (!$this->recupererContact($pseudo, $password)) {
        $retour = true;       
    }
    return $retour;
}


public function recupererContact(string $pseudo, string $email):bool{
    $sql = "SELECT * FROM personnes WHERE pseudo = :pseudo OR email = :email";
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
public function verifierAccesEtRecupererUtilisateur(string $pseudo): ?array {
    // Prépare la requête pour récupérer les données de l'utilisateur
    $sql = "SELECT * FROM personnes WHERE pseudo = :pseudo";
    $stmt = $this->db->prepare($sql);
    
    // Lier l'adresse e-mail
    $stmt->bindParam(':pseudo', $pseudo, \PDO::PARAM_STR);
    
    // Exécuter la requête
    $stmt->execute();
    
    // Récupérer les résultats
    $utilisateur = $stmt->fetch(\PDO::FETCH_ASSOC);
    
    // Retourne toutes les données de l'utilisateur
    return $utilisateur ? $utilisateur : null; // Si l'utilisateur existe, retourne ses données, sinon null
}

}