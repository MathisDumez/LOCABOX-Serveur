<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Model extends Main_Model {

    public function __construct() {
        parent::__construct();
    }

    // Récupérer les box avec tri sécurisé (ASC ou DESC)
    public function get_sorted_boxes($sort = 'num', $order = 'ASC') {
        $allowed_sorts = ['num', 'size', 'available'];
        $allowed_orders = ['ASC', 'DESC'];

        if (!in_array($sort, $allowed_sorts)) {
            $sort = 'num'; // Valeur par défaut
        }
        if (!in_array(strtoupper($order), $allowed_orders)) {
            $order = 'ASC'; // Valeur par défaut
        }

        $this->db->order_by($sort, $order);
        return $this->db->get('box')->result();
    }

    // Détails d'un box sécurisé
    public function get_box_details($id) {
        $id = (int) $id;

        if ($id <= 0) {
            log_message('error', "get_box_details() - ID de box invalide : $id");
            return null;
        }

        $this->db->select('id_box, num, size, id_warehouse, available');
        $this->db->from('box');
        $this->db->where('id_box', $id);
        $query = $this->db->get();

        if ($query->num_rows() === 0) {
            log_message('error', "get_box_details() - Box introuvable avec ID : $id");
            return null;
        }

        return $query->row();
    }

    // Vérifier si un box est disponible pour une période donnée
    public function is_box_available($box_id, $start_date, $end_date) {
        $this->db->where('box_id', (int) $box_id);
        $this->db->where('start_date <=', $end_date);
        $this->db->where('end_date >=', $start_date);
        $query = $this->db->get('rent');

        return $query->num_rows() === 0;
    }

    // Récupérer tous les box disponibles pour une période donnée
    public function get_available_boxes($start_date, $end_date) {
        $subquery = $this->db->select('box_id')
                             ->from('rent')
                             ->where('start_date <=', $end_date)
                             ->where('end_date >=', $start_date)
                             ->get_compiled_select();

        $this->db->where("id_box NOT IN ($subquery)", null, false);
        return $this->db->get('box')->result();
    }
}
?>
