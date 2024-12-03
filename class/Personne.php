<?php
use \Exception;
class Personne {

    private $id;
    private $nom;
    private $prenom;
    private $email;
    private $noTel;
    private $mdp;

    public function __construct(string $prenom, string $nom, string $email, string $noTel, string $mdp, int $id = 0) {
        if (empty($prenom)) {
            throw new Exception('Il faut un prénom');
        }
        if (empty($nom)) {
            throw new Exception('Il faut un nom');
        }
        if (empty($email)) {
            throw new Exception('Il faut un email');
        }
        if (empty($noTel)) {
            throw new Exception('Il faut un numéro de téléphone');
        }
        if (empty($mdp)) {
            throw new Exception('Le mot de passe est vide');
        }
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }

        $this->prenom = $prenom;
        $this->nom = $nom;
        $this->email = $email;
        $this->noTel = $noTel;
        $this->mdp = $mdp;
        $this->id = $id;
    }

    //Récupérer les données
        /**
     * Rend l'id de la personne
     * @return int L'identifiant
     */
    public function rendId(): int {
        return $this->id;
    }

    /**
     * Defini l'id de la personne
      @param int $id Identifiant de la personne
     */
    public function definiId($id): void {
        if ($id > 0) {
            $this->id = $id;
        }
    }
    /**
     * Rend le prénom
     * @return string Le prénom
     */
    public function rendPrenom(): string {
        return $this->prenom;
    }

        /**
     * Rend le nom
     * @return string Le nom
     */
    public function rendNom(): string {
        return $this->nom;
    }

        /**
     * Rend l'email
     * @return string L'email
     */
    public function rendEmail(): string {
        return $this->email;
    }

        /**
     * Rend le numéro de téléphone
     * @return string Le numéro de téléphone
     */
    public function rendNoTel(): string {
        return $this->noTel;
    }

    public function rendMdp(): string { 
        return $this->mdp; 
    }


}