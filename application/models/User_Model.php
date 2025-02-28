<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class User_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer un utilisateur par son email
    public function get_user_by_email($email) {
        $email = trim($email);
        return filter_var($email, FILTER_VALIDATE_EMAIL) 
            ? $this->db->get_where('user_box', ['email' => $email])->row() 
            : null;
    }

    // Mise à jour du mot de passe
    public function update_password($id_user_box, $new_password) {
        if ($id_user_box <= 0 || strlen($new_password) < 8) {
            return false;
        }
        return $this->update('user_box', $id_user_box, ['password' => password_hash($new_password, PASSWORD_ARGON2I)]);
    }
}
?>