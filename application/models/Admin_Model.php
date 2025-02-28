<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer tous les utilisateurs
    public function get_all_users() {
        return $this->get_all('user_box');
    }

    // Modifier les rôles ou informations d'un utilisateur
    public function update_user($id_user_box, $data) {
        return $this->update('user_box', $id_user_box, $data);
    }

    // Supprimer un utilisateur
    public function delete_user($id_user_box) {
        return $this->delete('user_box', $id_user_box);
    }

    // Récupérer la liste des box
    public function get_all_boxes() {
        return $this->get_all('box');
    }

    // Modifier un box
    public function update_box($id_box, $data) {
        return $this->update('box', $id_box, $data);
    }
}
?>