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
    
        $this->db->select('box.num AS box_num, box.size AS box_size, warehouse.name AS warehouse_name, rent.start_reservation_date, rent.end_reservation_date, rent.status');
        $this->db->from('rent');
        $this->db->join('box', 'box.id_box = rent.id_box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse'); // Jointure pour récupérer le bâtiment
        $this->db->where('rent.id_user_box', $id_user_box);
        $this->db->order_by('rent.start_reservation_date', 'DESC');
    
        return $this->db->get()->result();
    } 
}
?>