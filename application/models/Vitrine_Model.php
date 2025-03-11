<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer les box avec filtres et tri sécurisé
    public function get_filtered_boxes($filters = [], $sort = 'num', $order = 'ASC') {
        $allowed_sorts = ['num', 'size', 'available', 'id_warehouse'];
        $allowed_orders = ['ASC', 'DESC'];

        $sort = in_array($sort, $allowed_sorts, true) ? $sort : 'num';
        $order = in_array(strtoupper($order), $allowed_orders, true) ? strtoupper($order) : 'ASC';        

        $this->db->select('box.*, warehouse.name AS warehouse_name')
                 ->from('box')
                 ->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');

        // Appliquer dynamiquement les filtres s'ils existent et sont valides
        if (!empty($filters['size']) && is_numeric($filters['size'])) {
            $this->db->where('box.size', (int) $filters['size']);
        }
        if (isset($filters['available']) && ($filters['available'] === '0' || $filters['available'] === '1')) {
            $this->db->where('box.available', (int) $filters['available']);
        }
        if (!empty($filters['warehouse']) && is_numeric($filters['warehouse'])) {
            $this->db->where('box.id_warehouse', (int) $filters['warehouse']);
        }

        return $this->db->order_by($sort, $order)->get()->result();
    }

    // Récupérer la liste des bâtiments
    public function get_warehouses() {
        return $this->get_all('warehouse');
    }

    public function get_box_details($id) {
        return $this->db->select('box.*, warehouse.name AS warehouse_name')
                        ->from('box')
                        ->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left')
                        ->where('box.id_box', $id)
                        ->get()
                        ->row();
    }    
}
?>