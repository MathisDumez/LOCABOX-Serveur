<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Main_Model extends CI_Model {
    
    public function __construct() {
        parent::__construct();
    }

    // Fonction générique pour récupérer toutes les données d'une table
    public function get_all($table) {
        return $this->db->get($table)->result();
    }

    // Fonction générique pour récupérer une ligne par ID
    public function get_by_id($table, $id) {
        return $this->db->get_where($table, ['id' => $id])->row();
    }

    // Fonction générique pour insérer des données
    public function insert($table, $data) {
        return $this->db->insert($table, $data);
    }

    // Fonction générique pour mettre à jour des données
    public function update($table, $id, $data) {
        return $this->db->where('id', $id)->update($table, $data);
    }

    // Fonction générique pour supprimer une entrée
    public function delete($table, $id) {
        return $this->db->where('id', $id)->delete($table);
    }
}
?>