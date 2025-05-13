<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code_Model extends Main_Model {
    public function __construct() {
        parent::__construct();
    }

    // Générer un nouveau code d'accès pour un box
    public function generate_access_code($id_box) {
        $id_box = (int) $id_box;
        if ($id_box <= 0) return false;
        
        $new_code = rand(100000, 999999); // Code à 6 chiffres
        $data = ['generated_code' => $new_code];
        return $this->update('box', $id_box, $data);
    }

    // Récupérer l'historique des codes d'un box
    public function get_code_history($id_box) {
        return $this->get_where('code_log', ['id_box' => (int) $id_box]);
    }

    /*** 
    SELECT box.num, box.current_code, warehouse.name 
    FROM box 
    INNER JOIN warehouse 
    ON box.id_warehouse = warehouse.id_warehouse
    ***/
    public function get_code_and_warehouse(){
        $this->db->select('box.num, box.current_code, warehouse.name as warehouse_name');
        $this->db->from('box');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse');
        $this->db->order_by('warehouse.name', 'ASC');
        $this->db->order_by('box.num', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

}
?>