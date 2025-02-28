<?php

declare(strict_types=1);

if (!defined('BASEPATH')) exit('No direct script access allowed');

class Identification_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Vérifier l'utilisateur avec email et mot de passe
    public function check_user(string $email, string $password): ?object {
        $email = trim($email);

        // Validation de l'email
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            log_message('error', "Email invalide : " . $email);
            return null;
        }

        // Récupération de l'utilisateur par email
        $user = $this->db->get_where('user_box', ['email' => $email])->row();

        // Vérification si l'utilisateur existe
        if (!$user) {
            log_message('error', "Aucun utilisateur trouvé avec l'email : " . $email);
            return null;
        }

        // Vérification du mot de passe
        if (password_verify($password, $user->password)) {
            log_message('error', "password_verify() : VRAI");
            return $user;
        }

        log_message('error', "password_verify() : FAUX");
        return null;
    }

    // Inscription utilisateur
    public function register_user(array $data): bool {
        $email = trim($data['email']);
        
        // Validation de l'email et vérification de l'existence
        if (!filter_var($email, FILTER_VALIDATE_EMAIL) || $this->check_email_exists($email)) {
            log_message('error', "Email invalide ou déjà utilisé : " . $email);
            return false;
        }

        // Hachage du mot de passe
        if (!$hashed_password = password_hash($data['password'], PASSWORD_ARGON2I)) {
            log_message('error', "Erreur lors du hachage du mot de passe");
            return false;
        }
        log_message('error', "Données : " . $data['password']);
        log_message('error', "Mot de passe haché : " . $hashed_password);
        $data['password'] = $hashed_password;
        
        // Insertion de l'utilisateur
        return $this->db->insert('user_box', $data);
    }

    // Vérifier si un email existe
    public function check_email_exists(string $email): bool {
        return $this->db->get_where('user_box', ['email' => $email])->num_rows() > 0;
    }
}