<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Batiment_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Batiment_Model');
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }

    public function gestion_batiment($page = 1) {
        $this->check_admin();

        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $this->load->helper('pagination_helper');
        $total = $this->Batiment_Model->count('warehouse');
        
        init_pagination(site_url('admin/gestion_batiment'), $total, $per_page, 3);

        $data['warehouses'] = $this->Batiment_Model->get_paginated_warehouse($per_page, $offset);
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('gestion_batiment', $data);
    }

    public function afficher_ajouter_batiment() {
        $this->check_admin();
        $this->load->view('ajouter_batiment');
    }
    
    public function ajouter_batiment() {
        $this->check_admin();
    
        $this->form_validation->set_rules('name', 'Nom du bâtiment', 'required|max_length[50]');
        $this->form_validation->set_rules('address', 'Adresse', 'required|max_length[50]');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/ajouter_batiment');
        }
    
        $name = $this->input->post('name');
        $address = $this->input->post('address');
    
        // Vérifie l'unicité du bâtiment
        $existing = $this->Batiment_Model->get_where('warehouse', ['name' => $name, 'address' => $address]);
        if (!empty($existing)) {
            $this->session->set_flashdata('error', 'Ce bâtiment existe déjà.');
            redirect('admin/ajouter_batiment');
        }
    
        $success = $this->Batiment_Model->insert('warehouse', [
            'name' => $name,
            'address' => $address
        ]);
    
        if ($success) {
            $this->session->set_flashdata('success', 'Bâtiment ajouté avec succès.');
            redirect('admin/gestion_batiment');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout du bâtiment.');
            redirect('admin/ajouter_batiment');
        }
    }
    
    public function afficher_modifier_batiment($id) {
        $this->check_admin();
    
        $warehouse = $this->Batiment_Model->get_by_id('warehouse', $id, 'id_warehouse');
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Bâtiment introuvable.');
            redirect('admin/gestion_batiment');
        }
    
        $data['warehouse'] = $warehouse;
        $this->load->view('modifier_batiment', $data);
    }
    
    public function modifier_batiment($id) {
        $this->check_admin();
    
        $this->form_validation->set_rules('name', 'Nom', 'required|max_length[50]');
        $this->form_validation->set_rules('address', 'Adresse', 'required|max_length[50]');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/modifier_batiment/' . $id);
        }
    
        $data = [
            'name' => $this->input->post('name'),
            'address' => $this->input->post('address')
        ];
    
        $success = $this->Batiment_Model->update('warehouse', $id, $data, 'id_warehouse');
    
        if ($success) {
            $this->session->set_flashdata('success', 'Bâtiment modifié avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
        }
    
        redirect('admin/gestion_batiment');
    }
    
    public function supprimer_batiment($id) {
        $this->check_admin();
    
        // Vérifie si le bâtiment existe
        $warehouse = $this->Batiment_Model->get_by_id('warehouse', $id, 'id_warehouse');
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Bâtiment introuvable.');
            redirect('admin/gestion_batiment');
        }
    
        // Vérifie s’il existe des boxs dans ce bâtiment avant suppression
        $boxes = $this->Batiment_Model->get_where('box', ['id_warehouse' => $id]);
        if (!empty($boxes)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer ce bâtiment car des boxs y sont encore associées.');
            redirect('admin/gestion_batiment');
        }
    
        $deleted = $this->Batiment_Model->delete('warehouse', $id, 'id_warehouse');
    
        if ($deleted) {
            $this->session->set_flashdata('success', 'Bâtiment supprimé avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression.');
        }
    
        redirect('admin/gestion_batiment');
    }    
}
?>