<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Récupérer les box avec filtres et tri sécurisé
    public function get_filtered_boxes($filters = [], $sort = 'available', $order = 'DESC') {
        $this->db->select('box.*, warehouse.name AS warehouse_name')
                 ->from('box')
                 ->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
    
        // Appliquer les filtres s'ils existent
        if (!empty($filters['size']) && is_numeric($filters['size'])) {
            $this->db->where('box.size', (int) $filters['size']);
        }
        if (isset($filters['available']) && ($filters['available'] === '0' || $filters['available'] === '1')) {
            $this->db->where('box.available', (int) $filters['available']);
        }
        if (!empty($filters['warehouse']) && is_numeric($filters['warehouse'])) {
            $this->db->where('box.id_warehouse', (int) $filters['warehouse']);
        }
    
        // Tri par défaut : disponible en premier, puis bâtiment ASC, puis numéro ASC
        $this->db->order_by('box.available', 'DESC')
                 ->order_by('box.id_warehouse', 'ASC')
                 ->order_by('box.num', 'ASC');
    
        return $this->db->get()->result();
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

    public function count_filtered_boxes($filters = []) {
        $conditions = [];
        if (!empty($filters['size']) && is_numeric($filters['size'])) {
            $conditions['box.size'] = (int)$filters['size'];
        }
        if (isset($filters['available']) && ($filters['available'] === '0' || $filters['available'] === '1')) {
            $conditions['box.available'] = (int)$filters['available'];
        }
        if (!empty($filters['warehouse']) && is_numeric($filters['warehouse'])) {
            $conditions['box.id_warehouse'] = (int)$filters['warehouse'];
        }

        $joins = [
            'warehouse' => [
                'table' => 'warehouse',
                'condition' => 'box.id_warehouse = warehouse.id_warehouse',
                'type' => 'left'
            ]
        ];

        return $this->count_filtered('box', $conditions, $joins);
    }

    public function get_paginated_filtered_boxes($filters = [], $limit = 10, $offset = 0) {
        $conditions = [];
        if (!empty($filters['size']) && is_numeric($filters['size'])) {
            $conditions['box.size'] = (int)$filters['size'];
        }
        if (isset($filters['available']) && ($filters['available'] === '0' || $filters['available'] === '1')) {
            $conditions['box.available'] = (int)$filters['available'];
        }
        if (!empty($filters['warehouse']) && is_numeric($filters['warehouse'])) {
            $conditions['box.id_warehouse'] = (int)$filters['warehouse'];
        }

        $joins = [
            'warehouse' => [
                'table' => 'warehouse',
                'condition' => 'box.id_warehouse = warehouse.id_warehouse',
                'type' => 'left',
                'select' => ['warehouse.name AS warehouse_name']
            ]
        ];

        $order_by = [
            'box.available' => 'DESC',
            'box.id_warehouse' => 'ASC',
            'box.num' => 'ASC'
        ];

        return $this->get_paginated('box', $limit, $offset, $conditions, $joins, $order_by);
    }

}
?>