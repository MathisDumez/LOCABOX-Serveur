<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Box_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Box_Model');
        $this->load->library(['session', 'form_validation']);
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }

    public function etat_box() {
        $this->check_admin(); // Vérifie si l'utilisateur est admin
    
        $data['boxes'] = $this->Box_Model->get_all_boxes(); // Récupère la liste des box
        $data['warehouses'] = $this->Box_Model->get_all_warehouses(); // Récupère la liste des bâtiments
        
        $this->load->view('etat_box', $data); // Charge la vue etat_box.php
    }

    public function update_box($id_box) {
        $this->check_admin();
        $data = [
            'size' => $this->input->post('size'),
            'available' => $this->input->post('available')
        ];
        $this->Box_Model->update_box($id_box, $data);
        $this->session->set_flashdata('success', 'Box mis à jour.');
        redirect('admin/dashboard');
    }

    public function acces_box($id_box) {
        $this->check_admin(); // Vérifie que l'utilisateur est admin
        $data['access_logs'] = $this->Box_Model->get_access_logs_by_box($id_box);
        $this->load->view('acces_box', $data);
    }
    
    public function alarme_box($id_box) {
        $this->check_admin(); // Vérifie que l'utilisateur est admin
        $data['alarms'] = $this->Box_Model->get_alarm_logs_by_box($id_box);
        $this->load->view('alarme_box', $data);
    }    

    public function ajouter_box() {
        $this->check_admin(); // Vérifie que l'utilisateur est un admin
    
        // Validation des champs
        $this->form_validation->set_rules('num', 'Numéro du box', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('size', 'Taille', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('id_warehouse', 'Bâtiment', 'required|integer');
        $this->form_validation->set_rules('available', 'Disponibilité', 'required|in_list[0,1]');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/etat_box');
        }
    
        $num = $this->input->post('num');
        $size = $this->input->post('size');
        $id_warehouse = $this->input->post('id_warehouse');
        $available = $this->input->post('available');
    
        // Vérification que le bâtiment existe
        $warehouse = $this->Box_Model->get_by_id('warehouse', $id_warehouse, 'id_warehouse');
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Le bâtiment sélectionné n\'existe pas.');
            redirect('admin/etat_box');
        }
    
        // Vérification qu'il n'existe pas déjà un box avec le même numéro dans ce bâtiment
        $existing_box = $this->Box_Model->get_where('box', ['num' => $num, 'id_warehouse' => $id_warehouse]);
        if (!empty($existing_box)) {
            $this->session->set_flashdata('error', 'Un box avec ce numéro existe déjà dans ce bâtiment.');
            redirect('admin/etat_box');
        }
    
        // Insertion du box
        $data = [
            'num' => $num,
            'size' => $size,
            'id_warehouse' => $id_warehouse,
            'available' => $available,
            'current_code' => '000000',
        ];
    
        if ($this->Box_Model->ajouter_box($data)) {
            $this->session->set_flashdata('success', 'Box ajouté avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout du box.');
        }
    
        redirect('admin/etat_box');
    }
}
?>