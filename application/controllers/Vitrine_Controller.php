<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Vitrine_Model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    // Page principale avec filtres et tri
    public function index() {
        $filters = [
            'size' => $this->input->get('size', true),
            'available' => $this->input->get('available', true),
            'warehouse' => $this->input->get('warehouse', true)
        ];
        $sort = $this->input->get('sort', true) ?? 'num';
        $order = $this->input->get('order', true) ?? 'ASC';

        $data['boxes'] = $this->Vitrine_Model->get_filtered_boxes($filters, $sort, $order);
        $data['warehouses'] = $this->Vitrine_Model->get_warehouses(); // Récupération des bâtiments

        $this->load->view('vitrine_box', $data);
    }

    // Détails d'un box
    public function detail($id) {
        $id = (int) $id;
        $data['box'] = $this->Vitrine_Model->get_box_details($id);

        if (!$data['box']) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('Vitrine_Controller/index');
            return;
        }
        $this->load->view('page_box', $data);
    }
}
?>