<?php
//use Exception;
class Personne {

    private $id;
    private $pseudo;
    private $email;
    private $mdp;

    public function __construct(string $pseudo,  string $email, string $mdp, int $id = 0) {
        if (empty($pseudo)) {
            throw new Exception('Il faut un pseudo');
        }

        if (empty($email)) {
            throw new Exception('Il faut un email');
        }

        if (empty($mdp)) {
            throw new Exception('Le mot de passe est vide');
        }
        if ($id < 0) {
            throw new Exception('Il faut un id valide');
        }

        $this->pseudo = $pseudo;
        $this->email = $email;
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
     * Rend le pseudo
     * @return string Le pseudo
     */
    public function rendPseudo(): string {
        return $this->pseudo;
    }

        /**
     * Rend l'email
     * @return string L'email
     */
    public function rendEmail(): string {
        return $this->email;
    }


    public function rendMdp(): string { 
        return $this->mdp; 
    }


}