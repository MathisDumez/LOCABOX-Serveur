<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Box_Model extends Main_Model {
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
}
?>