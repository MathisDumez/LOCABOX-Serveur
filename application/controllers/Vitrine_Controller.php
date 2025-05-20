<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Vitrine_Model');
    }

    // Page principale avec filtres et tri
    public function index($page = 1) {
        $filters = [
            'size' => $this->input->get('size', true),
            'available' => $this->input->get('available', true),
            'warehouse' => $this->input->get('warehouse', true)
        ];

        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $total = $this->Vitrine_Model->count_filtered_boxes($filters);

        $base_url = site_url('vitrine/index');
        init_pagination($base_url, $total, $per_page, 3, $this->input->get());

        $data['boxes'] = $this->Vitrine_Model->get_paginated_filtered_boxes($filters, $per_page, $offset);
        $data['warehouses'] = $this->Vitrine_Model->get_warehouses();
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('vitrine_box', $data);
    }

    // Détails d'un box
    public function detail($id) {
        $id = (int) $id;
        $data['box'] = $this->Vitrine_Model->get_box_details($id);

        if (!$data['box']) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('vitrine/index');
            return;
        }
        $this->load->view('page_box', $data);
    }
}
?>