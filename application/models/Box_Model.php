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
        $this->db->select('
            box.*, 
            warehouse.name as warehouse_name, 
            IF(
                TIMESTAMPDIFF(SECOND, box.state, NOW()) <= 60,
                "Connecté",
                "Non Connecté"
            ) as connection_status
        ');
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
        return $this->db->update('box', $data, ['id_box' => $id_box]);
    }    

    public function get_access_logs_by_box_paginated($id_box, $limit, $offset) {
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
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_access_logs_by_box($id_box) {
        $this->db->from('access_log');
        $this->db->where('id_box', $id_box);
        return $this->db->count_all_results();
    }
    
    public function get_alarm_logs_by_box_paginated($id_box, $limit, $offset) {
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
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_alarm_logs_by_box($id_box) {
        $this->db->from('alarm_log');
        $this->db->where('id_box', $id_box);
        return $this->db->count_all_results();
    }

    public function ajouter_box($data) {
        return $this->insert('box', $data);
    }

    public function get_box_details($id_box) {
        $this->db->select('
            box.*,
            warehouse.name AS warehouse_name,
            warehouse.address AS warehouse_address,
            rent.rent_number,
            rent.start_reservation_date,
            rent.end_reservation_date,
            rent.status,
            user_box.email,
            IF(
                TIMESTAMPDIFF(SECOND, box.state, NOW()) <= 60,
                "Connecté",
                "Non Connecté"
            ) as connection_status
        ');
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
        $this->db->join('rent', 'box.id_box = rent.id_box AND rent.status IN ("Validée", "En Cours")', 'left');
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left');
        $this->db->where('box.id_box', $id_box);
        return $this->db->get()->row();
    }

    // Compte total filtré (pour pagination)
    public function count_filtered_boxes(array $conditions = [], ?string $connection_condition = null) {
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');

        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $this->db->where($field, $value);
            }
        }
        if ($connection_condition) {
            $this->db->where($connection_condition);
        }

        return $this->db->count_all_results();
    }

    // Récupérer les boxes paginées avec filtre
    public function get_paginated_boxes_filtered($limit, $offset, array $conditions = [], ?string $connection_condition = null) {
        $this->db->select('
            box.*, 
            warehouse.name as warehouse_name, 
            IF(
                TIMESTAMPDIFF(SECOND, box.state, NOW()) <= 60,
                "Connecté",
                "Non Connecté"
            ) as connection_status
        ');
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');

        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                $this->db->where($field, $value);
            }
        }
        if ($connection_condition) {
            $this->db->where($connection_condition);
        }

        $this->db->order_by('warehouse.name', 'ASC');
        $this->db->order_by('box.num', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

}
?>