<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer les box avec tri sécurisé
    public function get_sorted_boxes($sort = 'num', $order = 'ASC') {
        $allowed_sorts = ['num', 'size', 'available', 'id_warehouse'];
        $allowed_orders = ['ASC', 'DESC'];

        if (!in_array($sort, $allowed_sorts)) {
            $sort = 'num';
        }
        if (!in_array(strtoupper($order), $allowed_orders)) {
            $order = 'ASC';
        }

        return $this->db->order_by($sort, $order)->get('box')->result();
    }

    // Détails d'un box sécurisé avec jointure
    public function get_box_details($id) {
        $id = (int) $id;
        if ($id <= 0) return null;

        return $this->db->select('box.*, warehouse.name AS warehouse_name')
                        ->from('box')
                        ->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left')
                        ->where('box.id_box', $id)
                        ->get()
                        ->row();
    }
}
?>
