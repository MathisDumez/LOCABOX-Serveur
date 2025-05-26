<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Code_Model');
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }

    public function gestion_code($page = 1) {
        $this->check_admin();
        $this->load->helper('pagination_helper');

        $per_page = 10;
        $page = $this->uri->segment(3) ?? 1;
        $offset = ($page - 1) * $per_page;

        // Récupération des filtres depuis $_GET
        $filters = [
            'warehouse' => $this->input->get('warehouse', TRUE),
            'box_num' => $this->input->get('box_num', TRUE)
        ];

        $total_boxes = $this->Code_Model->count_filtered_boxes($filters);
        init_pagination(site_url('admin/gestion_code'), $total_boxes, $per_page, 3);

        $data['boxes'] = $this->Code_Model->get_filtered_boxes($per_page, $offset, $filters);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['warehouses'] = $this->Code_Model->get_all_warehouses(); // pour <select>

        $this->load->view('gestion_code', $data);
    }

    public function generer_code($id_box) {
        $this->check_admin();

        $box = $this->Code_Model->get_box_by_id($id_box);

        if (!$box) {
            show_error("Box introuvable.");
        }

        $new_code = $this->generate_unique_code($box->id_box);
        if (!$new_code) {
            $this->session->set_flashdata('error', 'Impossible de générer un code unique non utilisé ces 12 derniers mois.');
            redirect('admin/gestion_code');
        }

        $this->Code_Model->update_current_code($box->id_box, $new_code);
        $this->Code_Model->insert_code_log($box->id_box, $new_code);

        $this->session->set_flashdata('success', "Nouveau code généré pour le Box {$box->num} {$box->warehouse_name} : $new_code");
        redirect('admin/gestion_code');
    }

    private function generate_unique_code($id_box) {
        $tries = 0;
        do {
            $code = str_pad(mt_rand(0, 999999), 6, '0', STR_PAD_LEFT);
            $used = $this->Code_Model->has_code_been_used_recently($id_box, $code);
            $tries++;
        } while ($used && $tries < 20);

        return $used ? false : $code;
    }

    public function historique_code($id_box, $page = 1) {
        $this->check_admin();
        $this->load->helper('pagination_helper');

        $per_page = 10;
        $page = is_numeric($page) ? $page : 1;
        $offset = ($page - 1) * $per_page;

        $total_entries = $this->Code_Model->count_code_history($id_box);
        init_pagination(site_url("admin/historique_code/$id_box"), $total_entries, $per_page, 4);

        $data['history'] = $this->Code_Model->get_code_history_paginated($id_box, $per_page, $offset);
        $data['pagination_links'] = $this->pagination->create_links();

        $data['box'] = $this->db->get_where('box', ['id_box' => $id_box])->row();

        $this->load->view('historique_code', $data);
    }
}
