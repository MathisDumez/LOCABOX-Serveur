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
        $this->db->select('box.*, warehouse.name as warehouse_name');
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
        return $this->db->get()->result();
    }

    // Modifier un box
    public function update_box($id_box, $data) {
        return $this->update('box', $id_box, $data);
    }

    public function get_access_logs_by_box($id_box) {
        $this->db->select('access_log.*, user_box.email as user_email');
        $this->db->from('access_log');
        $this->db->join('rent', 'access_log.id_box = rent.id_box', 'left');
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left');
        $this->db->where('access_log.id_box', $id_box);
        $this->db->order_by('access_log.access_date', 'DESC'); // Trier du plus récent au plus ancien
        return $this->db->get()->result();
    }
    
    public function get_alarm_logs_by_box($id_box) {
        return $this->get_where('alarm_log', ['id_box' => $id_box]);
    }    
}
?>