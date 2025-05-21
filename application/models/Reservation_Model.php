<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservation_Model extends Main_Model {
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
        if (!empty($filters['box_num'])) {
            $this->db->like('box.num', $filters['box_num']);
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
        return $this->update('rent', $rent_number, ['status' => 'Validée'], 'rent_number');
    }

    public function update_reservation_status() {
        $now = date('Y-m-d H:i:s');

        // 1. En Attente -> Annulée (si la date de début est dépassée)
        $this->db->where('status', 'En Attente');
        $this->db->where('start_reservation_date <', $now);
        $pending_reservations = $this->db->get('rent')->result();

        foreach ($pending_reservations as $reservation) {
            $this->update_reservation($reservation->rent_number, ['status' => 'Annulée']);
        }

        // 2. Validée -> En Cours (si la date de début est atteinte ou dépassée)
        $this->db->where('status', 'Validée');
        $this->db->where('start_reservation_date <=', $now);
        $validated_reservations = $this->db->get('rent')->result();

        foreach ($validated_reservations as $reservation) {
            $this->update_reservation($reservation->rent_number, ['status' => 'En Cours']);
        }

        // 3. En Cours -> Terminée (si la date de fin est dépassée)
        $this->db->where('status', 'En Cours');
        $this->db->where('end_reservation_date <', $now);
        $ongoing_reservations = $this->db->get('rent')->result();

        foreach ($ongoing_reservations as $reservation) {
            $this->update_reservation($reservation->rent_number, ['status' => 'Terminée']);
        }
    }

    public function get_reservations_paginated($limit, $offset, $filters = []) {
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

        if (!empty($filters['email'])) {
            $this->db->like('user_box.email', $filters['email']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('rent.start_reservation_date >=', $filters['start_date']);
            $this->db->where('rent.end_reservation_date <=', $filters['end_date']);
        }

        if (!empty($filters['size'])) {
            $this->db->where('box.size', $filters['size']);
        }
        if (!empty($filters['warehouse'])) {
            $this->db->where('warehouse.id_warehouse', $filters['warehouse']);
        }
        if (!empty($filters['box_num']) && is_numeric($filters['box_num'])) {
            $this->db->where('box.num', (int)$filters['box_num']);
        }
        if (!empty($filters['status'])) {
            $this->db->where('rent.status', $filters['status']);
        }

        $this->db->order_by('rent.rent_number', 'DESC');

        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_reservations_filtered($filters = []) {
        $this->db->from('rent');
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left');
        $this->db->join('box', 'rent.id_box = box.id_box', 'left');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');

        if (!empty($filters['email'])) {
            $this->db->like('user_box.email', $filters['email']);
        }

        if (!empty($filters['start_date']) && !empty($filters['end_date'])) {
            $this->db->where('rent.start_reservation_date >=', $filters['start_date']);
            $this->db->where('rent.end_reservation_date <=', $filters['end_date']);
        }

        if (!empty($filters['size'])) {
            $this->db->where('box.size', $filters['size']);
        }

        if (!empty($filters['warehouse'])) {
            $this->db->where('warehouse.id_warehouse', $filters['warehouse']);
        }

        if (!empty($filters['box_num'])) {
            $this->db->like('box.num', $filters['box_num']);
        }

        if (!empty($filters['status'])) {
            $this->db->where('rent.status', $filters['status']);
        }

        return $this->db->count_all_results();
    }

}
?>