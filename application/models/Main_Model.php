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

    // Compter les entrées avec conditions optionnelles
    public function count_filtered(string $table, array $conditions = [], array $joins = []) {
        $this->db->from($table);

        // Appliquer les jointures
        foreach ($joins as $alias => $join) {
            $this->db->join($join['table'], $join['condition'], $join['type'] ?? 'inner');
        }

        // Mapper les filtres vers les colonnes SQL réelles
        $filter_mapping = [
            'email' => 'user_box.email',
            'size' => 'box.size',
            'warehouse' => 'warehouse.id_warehouse',
            'status' => 'rent.status',
            'start_date' => 'rent.start_reservation_date',
            'end_date' => 'rent.end_reservation_date',
        ];

        foreach ($conditions as $key => $value) {
            if (empty($value)) continue; // Ne pas appliquer les filtres vides

            if (!isset($filter_mapping[$key])) continue; // Clé inconnue
            $column = $filter_mapping[$key];

            if ($key === 'email') {
                $this->db->like($column, $value);
            } elseif ($key === 'start_date') {
                $this->db->where("$column >=", $value);
            } elseif ($key === 'end_date') {
                $this->db->where($column . " <=", $value);
            } else {
                $this->db->where($column, $value);
            }
        }

        return $this->db->count_all_results();
    }

    // Récupérer des entrées paginées avec conditions, joins et tri
    public function get_paginated(string $table, int $limit, int $offset = 0, array $conditions = [], array $joins = [], array $order_by = []) {
        $this->db->select("$table.*");
        $this->db->from($table);

        // Joins éventuels
        foreach ($joins as $alias => $join) {
            $this->db->join($join['table'], $join['condition'], $join['type'] ?? 'inner');
            // Pour pouvoir sélectionner des colonnes des tables jointes :
            if (!empty($join['select'])) {
                foreach ($join['select'] as $col) {
                    $this->db->select($col);
                }
            }
        }

        // Conditions
        if (!empty($conditions)) {
            foreach ($conditions as $field => $value) {
                if (is_array($value) && count($value) === 2 && in_array($value[0], ['<', '>', '<=', '>=', '!=', 'like'])) {
                    $this->db->where("$field {$value[0]}", $value[1]);
                } else {
                    $this->db->where($field, $value);
                }
            }
        }

        // Tri
        if (!empty($order_by)) {
            foreach ($order_by as $field => $dir) {
                $this->db->order_by($field, $dir);
            }
        }

        // Limite et offset
        $this->db->limit($limit, $offset);

        return $this->db->get()->result();
    }
}