<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_boxes_with_warehouse($limit, $offset) {
        $this->db->select('box.*, warehouse.name as warehouse_name');
        $this->db->from('box');
        $this->db->join('warehouse', 'warehouse.id_warehouse = box.id_warehouse');
        $this->db->order_by('warehouse.name', 'ASC');
        $this->db->order_by('box.num', 'ASC');
        $this->db->limit($limit, $offset);
        return $this->db->get()->result();
    }

    public function count_all_boxes() {
        return $this->db->count_all('box');
    }

    public function has_code_been_used_recently($id_box, $code) {
        $one_year_ago = date('Y-m-d H:i:s', strtotime('-1 year'));
        $this->db->where('id_box', $id_box);
        $this->db->where('code', $code);
        $this->db->where('code_date >=', $one_year_ago);
        return $this->db->count_all_results('code_log') > 0;
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

    public function get_box_by_num($num) {
        return $this->db->get_where('box', ['num' => $num])->row();
    }

    public function get_code_history($id_box) {
        $this->db->where('id_box', $id_box);
        $this->db->order_by('code_date', 'DESC');
        return $this->db->get('code_log')->result();
    }
}