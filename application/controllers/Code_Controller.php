<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Code_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Code_Model');
        $this->load->library(['session']);
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('Vitrine_Controller/index');
        }
    }

    public function gestion_code() {
        $this->check_admin();
        $data['boxes'] = $this->Code_Model->get_all('box');
        $this->load->view('gestion_code', $data);
    }

    public function generate_code($id_box) {
        $this->check_admin();
        if ($this->Code_Model->generate_access_code($id_box)) {
            $this->session->set_flashdata('success', 'Code généré avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la génération du code.');
        }
        redirect('Code_Controller/gestion_code');
    }

    public function historique_code($id_box) {
        $this->check_admin();
        $data['codes'] = $this->Code_Model->get_code_history($id_box);
        $this->load->view('historique_code', $data);
    }
}
?>