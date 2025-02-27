<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Identification_Model extends Main_Model {

    public function __construct() {
        parent::__construct();
    }

    // Vérifier si un utilisateur existe avec son email et mot de passe
    public function check_user($email, $password) {
        log_message('debug', 'Tentative de connexion pour l\'email : ' . $email);

        // Nettoyage de l'email
        $email = trim($email);

        // Vérifier le format de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Format d\'email invalide : ' . $email);
            return false;
        }

        // Récupération de l'utilisateur
        $this->db->where('email', $email);
        $query = $this->db->get('user_box');

        if ($query->num_rows() !== 1) {
            log_message('error', 'Échec de connexion (identifiants invalides)');
            return false;
        }

        $user = $query->row();

        // Vérification du mot de passe
        if (empty($user->password) || !password_verify($password, $user->password)) {
            log_message('error', 'Échec de connexion (identifiants invalides)');
            return false;
        }

        log_message('debug', 'Connexion réussie pour : ' . $email);
        return $user;
    }

    // Inscrire un nouvel utilisateur
    public function register_client($data) {
        // Nettoyage de l'email
        $data['email'] = trim($data['email']);

        // Vérification du format d'email
        if (!filter_var($data['email'], FILTER_VALIDATE_EMAIL)) {
            log_message('error', 'Inscription échouée : Email invalide');
            return false;
        }

        // Vérifier si l'email existe déjà
        if ($this->db->get_where('user_box', ['email' => $data['email']])->num_rows() > 0) {
            log_message('error', 'Inscription échouée : Email déjà utilisé');
            return false;
        }

        // Vérifier si le mot de passe est déjà haché (évite un double hash)
        if (!password_get_info($data['password'])['algo']) {
            $data['password'] = password_hash($data['password'], PASSWORD_ARGON2I);
        }

        if ($this->insert('user_box', $data)) {
            log_message('debug', 'Nouvel utilisateur inscrit : ' . $data['email']);
            return true;
        } else {
            log_message('error', 'Échec de l\'inscription pour : ' . $data['email']);
            return false;
        }
    }

    // Vérifier si un email existe déjà
    public function check_email_exists($email) {
        $query = $this->db->get_where('user_box', ['email' => $email]);
        return $query->num_rows() > 0;
    }
}
?>