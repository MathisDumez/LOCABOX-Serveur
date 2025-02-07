<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class User_Model extends Main_Model {

    public function __construct() {
        parent::__construct();
    }
    
    // Fonction spécifique pour récupérer un utilisateur par email
    public function get_by_email($email) {
        return $this->db->get_where('users', ['email' => $email])->row();
    }
    }
?>