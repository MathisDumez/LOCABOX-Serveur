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
            return ['status' => false, 'message' => 'Mot de passe trop court ou identifiant incorrect.'];
        }
        
        // Hachage du nouveau mot de passe
        $hashed_password = password_hash($new_password, PASSWORD_ARGON2I);
        
        // Mise à jour du mot de passe
        $this->db->where('id_user_box', $id_user_box); // Utilisation de 'id_user_box' pour la condition WHERE
        $update_status = $this->db->update('user_box', ['password' => $hashed_password]); // Mise à jour de la table user_box
        
        if ($update_status) {
            return ['status' => true, 'message' => 'Mot de passe mis à jour avec succès.'];
        }
        
        return ['status' => false, 'message' => 'Erreur lors de la mise à jour du mot de passe.'];
    }
    
    public function get_reservations($id_user_box) {
        if (!$id_user_box) {
            return [];
        }
    
        $this->db->select('rent.rent_number, box.num AS box_num, box.size AS box_size, warehouse.name AS warehouse_name, rent.start_reservation_date, rent.end_reservation_date, rent.status');
        $this->db->from('rent');
        $this->db->join('box', 'box.id_box = rent.id_box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse'); 
        $this->db->where('rent.id_user_box', $id_user_box);
        $this->db->order_by('rent.start_reservation_date', 'DESC');
    
        return $this->db->get()->result();
    }
    
    public function get_filtered_reservations($id_user_box, $size = null, $warehouse = null, $status = null) {
        $this->db->select('rent.rent_number, box.num AS box_num, box.size AS box_size, warehouse.name AS warehouse_name, rent.start_reservation_date, rent.end_reservation_date, rent.status');
        $this->db->from('rent');
        $this->db->join('box', 'box.id_box = rent.id_box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');
        $this->db->where('rent.id_user_box', $id_user_box);
    
        if (!empty($size)) {
            $this->db->where('box.size', $size);
        }
        if (!empty($warehouse)) {
            $this->db->where('box.id_warehouse', $warehouse);
        }
        if (!empty($status)) {
            $this->db->where('rent.status', $status);
        }
    
        $this->db->order_by('rent.start_reservation_date', 'DESC');
        return $this->db->get()->result();
    }    
    
    // Nouvelle fonction pour récupérer les statuts distincts pour le filtrage
    public function get_distinct_status() {
        $this->db->distinct();
        $this->db->select('status');
        $this->db->from('rent');
        return $this->db->get()->result();
    }
    
    public function cancel_reservation($rent_number, $id_user_box) {
        // Vérifier que la réservation appartient bien à l'utilisateur
        $this->db->where('rent_number', $rent_number);
        $this->db->where('id_user_box', $id_user_box);
        $this->db->where('status !=', 'Annulée'); // Empêcher l'annulation d'une réservation déjà annulée
    
        // Modifier le statut en "Annulée"
        return $this->db->update('rent', ['status' => 'Annulée']);
    }    
    
    // Compte le nombre total de réservations filtrées pour un utilisateur
    public function count_filtered_reservations($id_user_box, $size = null, $warehouse = null, $status = null) {
        $this->db->from('rent');
        $this->db->join('box', 'box.id_box = rent.id_box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');
        $this->db->where('rent.id_user_box', $id_user_box);

        if (!empty($size)) {
            $this->db->where('box.size', $size);
        }
        if (!empty($warehouse)) {
            $this->db->where('box.id_warehouse', $warehouse);
        }
        if (!empty($status)) {
            $this->db->where('rent.status', $status);
        }

        return $this->db->count_all_results();
    }

    // Récupère les réservations paginées avec filtres
    public function get_paginated_reservations($id_user_box, $limit, $offset = 0, $size = null, $warehouse = null, $status = null) {
        $this->db->select('rent.rent_number, box.num AS box_num, box.size AS box_size, warehouse.name AS warehouse_name, rent.start_reservation_date, rent.end_reservation_date, rent.status');
        $this->db->from('rent');
        $this->db->join('box', 'box.id_box = rent.id_box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');
        $this->db->where('rent.id_user_box', $id_user_box);

        if (!empty($size)) {
            $this->db->where('box.size', $size);
        }
        if (!empty($warehouse)) {
            $this->db->where('box.id_warehouse', $warehouse);
        }
        if (!empty($status)) {
            $this->db->where('rent.status', $status);
        }

        $this->db->order_by('rent.start_reservation_date', 'DESC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
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
}
?>