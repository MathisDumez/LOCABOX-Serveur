<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    // Récupérer toutes les données d'une table
    public function get_all($table) {
        return $this->db->get($table)->result();
    }

    // Récupérer une ligne par ID avec clé primaire personnalisée
    public function get_by_id($table, $id, $primary_key = 'id') {
        return $this->db->get_where($table, [$primary_key => $id])->row();
    }

    // Récupérer des entrées avec une condition
    public function get_where($table, $conditions = []) {
        return $this->db->get_where($table, $conditions)->result();
    }

    // Insérer une entrée
    public function insert($table, $data) {
        return $this->db->insert($table, $data);
    }

    // Insérer plusieurs entrées en une seule requête
    public function insert_batch($table, $data) {
        return $this->db->insert_batch($table, $data);
    }

    // Mettre à jour une entrée avec clé primaire personnalisée
    public function update($table, $id, $data, $primary_key = 'id') {
        return $this->db->where($primary_key, $id)->update($table, $data);
    }

    // Mettre à jour plusieurs entrées en une seule requête
    public function update_batch($table, $data, $primary_key) {
        return $this->db->update_batch($table, $data, $primary_key);
    }

    // Supprimer une entrée avec clé primaire personnalisée
    public function delete($table, $id, $primary_key = 'id') {
        return $this->db->where($primary_key, $id)->delete($table);
    }

    // Compter les entrées avec conditions optionnelles
    public function count($table, $conditions = []) {
        if (!empty($conditions)) {
            $this->db->where($conditions);
        }
        return $this->db->count_all_results($table);
    }
}
?>