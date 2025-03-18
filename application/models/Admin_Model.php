<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer tous les utilisateurs
    public function get_all_users() {
        return $this->get_all('user_box');
    }

    // Récupérer la liste des box
    public function get_all_boxes() {
        $this->db->select('box.*, warehouse.name as warehouse_name, 
            IFNULL(box.state, "Indisponible") as state');
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
        $this->db->order_by('warehouse.name', 'ASC');
        $this->db->order_by('box.num', 'ASC');
        return $this->db->get()->result();
    }    
      

    public function get_all_warehouses() {
        return $this->get_all('warehouse');
    }    

    // Modifier les rôles ou informations d'un utilisateur
    public function update_user($id_user_box, $data) {
        return $this->update('user_box', $id_user_box, $data);
    }

    // Supprimer un utilisateur
    public function delete_user($id_user_box) {
        return $this->delete('user_box', $id_user_box);
    }

    // Modifier un box
    public function update_box($id_box, $data) {
        return $this->update('box', $id_box, $data);
    }

    public function get_access_logs_by_box($id_box) {
        $this->db->select('
            access_log.access_date, 
            access_log.locked, 
            access_log.id_box, 
            box.num AS box_num, 
            warehouse.name AS warehouse_name,
            IF(MAX(rent.status) = "En Cours", MAX(user_box.email), NULL) AS user_email
        ', false);
        $this->db->from('access_log');
        $this->db->join('box', 'access_log.id_box = box.id_box', 'inner'); 
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'inner'); 
        $this->db->join('rent', 'access_log.id_box = rent.id_box', 'left'); 
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left'); 
        $this->db->where('access_log.id_box', $id_box);
        $this->db->group_by('access_log.access_date, access_log.locked, access_log.id_box, box.num, warehouse.name');
        $this->db->order_by('access_log.access_date', 'DESC');
    
        return $this->db->get()->result();
    }        
    
    public function get_alarm_logs_by_box($id_box) {
        $this->db->select('
            alarm_log.alarm_date, 
            alarm_log.info, 
            alarm_log.id_box, 
            box.num AS box_num, 
            warehouse.name AS warehouse_name,
            IF(MAX(rent.status) = "En Cours", MAX(user_box.email), NULL) AS user_email
        ', false);
        $this->db->from('alarm_log');
        $this->db->join('box', 'alarm_log.id_box = box.id_box', 'inner'); 
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'inner'); 
        $this->db->join('rent', 'alarm_log.id_box = rent.id_box', 'left'); 
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left'); 
        $this->db->where('alarm_log.id_box', $id_box);
        $this->db->group_by('alarm_log.alarm_date, alarm_log.info, alarm_log.id_box, box.num, warehouse.name');
        $this->db->order_by('alarm_log.alarm_date', 'DESC');
    
        return $this->db->get()->result();
    }
    
    public function ajouter_box($data) {
        return $this->insert('box', $data);
    }

    public function get_all_reservations($filters = []) {
        $this->db->select('
            rent.rent_number,
            rent.start_reservation_date, 
            rent.end_reservation_date, 
            rent.status, 
            user_box.email AS user_email, 
            box.num AS box_num,
            box.size AS box_size,
            warehouse.name AS warehouse_name
        ');
        $this->db->from('rent');
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left');
        $this->db->join('box', 'rent.id_box = box.id_box', 'left');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
    
        // Filtrer par email de l'utilisateur
        if (!empty($filters['email'])) {
            $this->db->like('user_box.email', $filters['email']);
        }
    
        // Filtrer les box entre deux dates
        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('rent.start_reservation_date >=', $filters['start_date']);
            $this->db->where('rent.end_reservation_date <=', $filters['end_date']);
        }
    
        // Autres filtres existants
        if (!empty($filters['size'])) {
            $this->db->where('box.size', $filters['size']);
        }
        if (!empty($filters['warehouse'])) {
            $this->db->where('warehouse.id_warehouse', $filters['warehouse']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('rent.status', $filters['status']);
        }
    
        return $this->db->get()->result();
    }
    
    public function update_reservation($rent_number, $data) {
        return $this->update('rent', $rent_number, $data, 'rent_number');
    }

    public function valider_reservation($rent_number) {
        return $this->update('rent', $rent_number, ['status' => 'En Cours'], 'rent_number');
    }    
    
}
?>