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

    public function gestion_box() {
        $this->check_admin(); // Vérifie si l'utilisateur est admin
    
        $data['boxes'] = $this->Box_Model->get_all_boxes(); // Récupère la liste des box
        $data['warehouses'] = $this->Box_Model->get_all_warehouses(); // Récupère la liste des bâtiments
        
        $this->load->view('gestion_box', $data); // Charge la vue gestion_box.php
    }

    public function gestion_box_page($page = 1) {
        $this->check_admin();

        $limit = 10; // Nombre de box par page
        $offset = ($page - 1) * $limit;

        $data['boxes'] = $this->Box_Model->get_boxes_paginated($limit, $offset);
        $data['warehouses'] = $this->Box_Model->get_all_warehouses();

        // Nombre total de box pour pagination
        $total_boxes = $this->Box_Model->count_all_boxes();
        $data['total_pages'] = ceil($total_boxes / $limit);
        $data['current_page'] = $page;

        $this->load->view('gestion_box', $data);
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
        $data['box'] = $this->Box_Model->get_by_id('box', $id_box, 'id_box');

        $this->load->view('acces_box', $data);
    }
    
    public function alarme_box($id_box) {
        $this->check_admin(); // Vérifie que l'utilisateur est admin

        $data['alarms'] = $this->Box_Model->get_alarm_logs_by_box($id_box);
        $data['box'] = $this->Box_Model->get_by_id('box', $id_box, 'id_box');

        $this->load->view('alarme_box', $data);
    }

    public function afficher_ajouter_box() {
        $this->check_admin();
        
        $data['warehouses'] = $this->Box_Model->get_all_warehouses();
        
        $this->load->view('ajouter_box', $data);
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
            redirect('admin/gestion_box');
        }
    
        $num = $this->input->post('num');
        $size = $this->input->post('size');
        $id_warehouse = $this->input->post('id_warehouse');
        $available = $this->input->post('available');
    
        // Vérification que le bâtiment existe
        $warehouse = $this->Box_Model->get_by_id('warehouse', $id_warehouse, 'id_warehouse');
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Le bâtiment sélectionné n\'existe pas.');
            redirect('admin/ajouter_box');
        }
    
        // Vérification qu'il n'existe pas déjà un box avec le même numéro dans ce bâtiment
        $existing_box = $this->Box_Model->get_where('box', ['num' => $num, 'id_warehouse' => $id_warehouse]);
        if (!empty($existing_box)) {
            $this->session->set_flashdata('error', 'Un box avec ce numéro existe déjà dans ce bâtiment.');
            redirect('admin/ajouter_box');
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
            redirect('admin/gestion_box');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout du box.');
            redirect('admin/ajouter_box');
        }
    }

    public function afficher_ajouter_batiment() {
        $this->check_admin();

        $data['warehouses'] = $this->Box_Model->get_all_warehouses();

        $this->load->view('ajouter_batiment', $data);
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
        $existing = $this->Box_Model->get_where('warehouse', ['name' => $name, 'address' => $address]);
        if (!empty($existing)) {
            $this->session->set_flashdata('error', 'Ce bâtiment existe déjà.');
            redirect('admin/ajouter_batiment');
        }
    
        $success = $this->Box_Model->insert('warehouse', [
            'name' => $name,
            'address' => $address
        ]);
    
        if ($success) {
            $this->session->set_flashdata('success', 'Bâtiment ajouté avec succès.');
            redirect('admin/ajouter_batiment');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout du bâtiment.');
            redirect('admin/ajouter_batiment');
        }
    }
    
    public function detail_box($id_box) {
        $this->check_admin(); // Admin only
    
        $data['box'] = $this->Box_Model->get_box_details($id_box);
    
        if (!$data['box']) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('admin/gestion_box');
        }
    
        $this->load->view('detail_box', $data);
    }
    
    public function modifier_box($id_box) {
        $this->check_admin();
    
        $box = $this->Box_Model->get_by_id('box', $id_box, 'id_box');
        $warehouses = $this->Box_Model->get_all_warehouses();
    
        if (!$box) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('admin/gestion_box');
        }
    
        $data['box'] = $box;
        $data['warehouses'] = $warehouses;
    
        $this->load->view('modifier_box', $data);
    }
    
    public function modifier_box_submit($id_box) {
        $this->check_admin();
    
        $this->form_validation->set_rules('num', 'Numéro', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('size', 'Taille', 'required|integer|greater_than[0]');
        $this->form_validation->set_rules('available', 'Disponibilité', 'required|in_list[0,1]');
        $this->form_validation->set_rules('id_warehouse', 'Bâtiment', 'required|integer');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/modifier_box/' . $id_box);
        }
    
        $data = [
            'num' => $this->input->post('num'),
            'size' => $this->input->post('size'),
            'available' => $this->input->post('available'),
            'id_warehouse' => $this->input->post('id_warehouse')
        ];
    
        $this->Box_Model->update_box($id_box, $data);
    
        $this->session->set_flashdata('success', 'Box mis à jour avec succès.');
        redirect('admin/detail_box/' . $id_box);
    }
    
    public function supprimer_box($id_box) {
        $this->check_admin();
    
        // Vérifier que le box existe
        $box = $this->Box_Model->get_by_id('box', $id_box, 'id_box');
        if (!$box) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('admin/gestion_box');
        }
    
        // Vérifie qu'il n'y a pas de location "En Cours" ou "Validée"
        $locations = $this->Box_Model->get_where('rent', [
            'id_box' => $id_box,
            'status' => 'En Cours'
        ]);
        if (!empty($locations)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer un box actuellement réservé.');
            redirect('admin/detail_box/' . $id_box);
        }
    
        // Supprimer le box
        $this->Box_Model->delete('box', $id_box, 'id_box');
    
        $this->session->set_flashdata('success', 'Box supprimé avec succès.');
        redirect('admin/gestion_box');
    }    
}
?>