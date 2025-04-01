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
        // Récupérer toutes les réservations avec le statut "Validée"
        $this->db->where('status', 'Validée');
        $this->db->where('start_reservation_date <=', date('Y-m-d H:i:s'));
        $valid_reservations = $this->db->get('rent')->result();
    
        foreach ($valid_reservations as $reservation) {
            // Si la réservation est en statut "Validée" et que la date de début est passée, la passer en statut "En Cours"
            $this->update_reservation($reservation->rent_number, ['status' => 'En Cours']);
        }
    
        // Récupérer toutes les réservations avec le statut "En Cours"
        $this->db->where('status', 'En Cours');
        $this->db->where('end_reservation_date <', date('Y-m-d H:i:s'));
        $ongoing_reservations = $this->db->get('rent')->result();
    
        foreach ($ongoing_reservations as $reservation) {
            // Si la réservation est en statut "En Cours" et que la date de fin est passée, la passer en statut "Terminée"
            $this->update_reservation($reservation->rent_number, ['status' => 'Terminée']);
        }
    }    
}
?>