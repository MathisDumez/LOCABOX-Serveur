<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Main_Model extends CI_Model {

    public function __construct() {
        parent::__construct();
    }

    // Récupérer toutes les données d'une table
    public function get_all(string $table) {
        return $this->db->get($table)->result();
    }

    // Récupérer une ligne par ID avec clé primaire personnalisée
    public function get_by_id(string $table, $id, string $primary_key = 'id') {
        return $this->db->get_where($table, [$primary_key => $id])->row();
    }

    // Récupérer des entrées avec une condition
    public function get_where(string $table, array $conditions = []) {
        return $this->db->get_where($table, $conditions)->result();
    }

    // Insérer une entrée
    public function insert(string $table, array $data) {
        return $this->db->insert($table, $data);
    }

    // Insérer plusieurs entrées en une seule requête
    public function insert_batch(string $table, array $data) {
        return $this->db->insert_batch($table, $data);
    }

    // Mettre à jour une entrée avec clé primaire personnalisée
    public function update(string $table, $id, array $data, string $primary_key = 'id') {
        return $this->db->update($table, $data, [$primary_key => $id]);
    }

    // Mettre à jour plusieurs entrées en une seule requête
    public function update_batch(string $table, array $data, string $primary_key) {
        return $this->db->update_batch($table, $data, $primary_key);
    }

    // Supprimer une entrée avec clé primaire personnalisée
    public function delete(string $table, $id, string $primary_key = 'id') {
        return $this->db->delete($table, [$primary_key => $id]);
    }

    // Compter les entrées avec conditions optionnelles
    public function count(string $table, array $conditions = []) {
        $this->db->where($conditions); // Applique les conditions seulement si elles sont fournies
        return $this->db->count_all_results($table);
    }
}