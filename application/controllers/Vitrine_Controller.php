<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Vitrine_Model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    // Page principale avec tri des box
    public function index() {
        $sort = $this->input->get('sort'); 
        $data['boxes'] = $this->Vitrine_Model->get_sorted_boxes($sort);
        $this->load->view('vitrine_box', $data);
    }

    // Détails d'un box
    public function detail($id) {
        $id = (int) $id;
        if ($id <= 0 || !($data['box'] = $this->Vitrine_Model->get_box_details($id))) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('Vitrine_Controller/index');
        }
        $this->load->view('page_box', $data);
    }
}
?>