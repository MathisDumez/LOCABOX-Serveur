<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Batiment_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    public function get_all_warehouses() {
        return $this->get_all('warehouse');
    }

    public function get_paginated_warehouse($limit, $offset) {
        return $this->db->get('warehouse', $limit, $offset)->result();
    }
}
?>