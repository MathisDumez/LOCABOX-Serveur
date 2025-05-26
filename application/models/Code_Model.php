<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_filtered_boxes($limit, $offset, $filters = []) {
        $this->db->select('box.*, warehouse.name as warehouse_name');
        $this->db->from('box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');

        if (!empty($filters['warehouse'])) {
            $this->db->where('box.id_warehouse', $filters['warehouse']);
        }

        if (!empty($filters['box_num']) && is_numeric($filters['box_num'])) {
            $this->db->where('box.num', (int)$filters['box_num']);
        }

        $this->db->order_by('warehouse.name', 'ASC');
        $this->db->order_by('box.num', 'ASC');
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }

    public function count_filtered_boxes($filters = []) {
        $this->db->from('box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');

        if (!empty($filters['warehouse'])) {
            $this->db->where('box.id_warehouse', $filters['warehouse']);
        }

        if (!empty($filters['box_num'])) {
            $this->db->like('box.num', $filters['box_num']);
        }

        return $this->db->count_all_results();
    }

    public function get_all_warehouses() {
        return $this->db->order_by('name', 'ASC')->get('warehouse')->result();
    }

    public function has_code_been_used_recently($id_box, $code) {
        $one_year_ago = date('Y-m-d H:i:s', strtotime('-1 year'));
        $this->db->where('id_box', $id_box);
        $this->db->where('code', $code);
        $this->db->where('code_date >=', $one_year_ago);
        return $this->db->count_all_results('code_log') > 0;
    }

    public function get_box_by_id($id_box) {
        $this->db->select('box.*, warehouse.name AS warehouse_name');
        $this->db->from('box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');
        $this->db->where('box.id_box', $id_box);
        return $this->db->get()->row();
    }

    public function update_current_code($id_box, $code) {
        $this->db->where('id_box', $id_box);
        return $this->db->update('box', ['current_code' => $code]);
    }

    public function insert_code_log($id_box, $code) {
        return $this->db->insert('code_log', [
            'id_box' => $id_box,
            'code' => $code,
            'code_date' => date('Y-m-d H:i:s')
        ]);
    }

    public function get_code_history_paginated($id_box, $limit, $offset) {
        $this->db->where('id_box', $id_box);
        $this->db->order_by('code_date', 'DESC');
        $this->db->limit($limit, $offset);
        return $this->db->get('code_log')->result();
    }

    public function count_code_history($id_box) {
        $this->db->where('id_box', $id_box);
        return $this->db->count_all_results('code_log');
    }
}