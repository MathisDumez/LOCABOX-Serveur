<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends Main_Model {

    public function __construct() {
        parent::__construct();
    }

    // Récupérer les informations d'un utilisateur par son email (avec validation)
    public function get_user_by_email($email) {
        // Nettoyage et validation de l'email
        $email = trim($email);
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Requête invalide pour un email mal formé : ' . $email);
            return null;
        }

        return $this->db->get_where('user_box', ['email' => $email])->row();
    }

    // Mettre à jour le mot de passe de l'utilisateur (avec validation)
    public function update_password($id_user_box, $new_password) {
        // Vérification de l'ID utilisateur
        $id_user_box = (int) $id_user_box;
        if ($id_user_box <= 0) {
            log_message('error', 'Tentative de mise à jour de mot de passe avec un ID invalide.');
            return false;
        }

        // Vérification de la complexité du mot de passe
        if (strlen($new_password) < 8) {
            log_message('error', 'Mot de passe trop court pour l\'ID utilisateur : ' . $id_user_box);
            return false;
        }

        // Hachage sécurisé du mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_ARGON2I);

        // Mise à jour du mot de passe
        return $this->update('user_box', $id_user_box, ['password' => $hashed_password]);
    }
}
?>