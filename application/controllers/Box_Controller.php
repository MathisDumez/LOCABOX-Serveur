<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Box_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Box_Model');
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }

    public function gestion_box($page = 1) {
        $this->check_admin();

        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        $size = $this->input->get('size');
        $id_warehouse = $this->input->get('id_warehouse');
        $available = $this->input->get('available');
        $connection_status = $this->input->get('connection_status');

        $conditions = [];
        if ($size) {
            $conditions['box.size'] = $size;
        }
        if ($id_warehouse) {
            $conditions['box.id_warehouse'] = $id_warehouse;
        }
        if ($available !== null && $available !== '') {
            $conditions['box.available'] = $available;
        }

        $connection_condition = null;
        if ($connection_status === 'Connecté') {
            $connection_condition = "TIMESTAMPDIFF(SECOND, box.state, NOW()) <= 60";
        } elseif ($connection_status === 'Non Connecté') {
            $connection_condition = "TIMESTAMPDIFF(SECOND, box.state, NOW()) > 60";
        }

        $this->load->helper('pagination_helper');
        $total = $this->Box_Model->count_filtered_boxes($conditions, $connection_condition);
        init_pagination(site_url('admin/gestion_box'), $total, $per_page, 3);

        $data['boxes'] = $this->Box_Model->get_paginated_boxes_filtered($per_page, $offset, $conditions, $connection_condition);
        $data['pagination_links'] = $this->pagination->create_links();

        $data['warehouses'] = $this->Box_Model->get_all_warehouses();

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

    public function acces_box($id_box, $page = 1) {
        $this->check_admin();

        $this->load->helper('pagination_helper');
        $per_page = 10;
        $page = is_numeric($page) ? $page : 1;
        $offset = ($page - 1) * $per_page;

        $total_logs = $this->Box_Model->count_access_logs_by_box($id_box);
        init_pagination(site_url("admin/acces_box/$id_box"), $total_logs, $per_page, 4);

        $data['access_logs'] = $this->Box_Model->get_access_logs_by_box_paginated($id_box, $per_page, $offset);
        $data['pagination_links'] = $this->pagination->create_links();
        $data['box'] = $this->Box_Model->get_by_id('box', $id_box, 'id_box');

        $this->load->view('acces_box', $data);
    }

    public function alarme_box($id_box, $page = 1) {
        $this->check_admin();

        $this->load->helper('pagination_helper');
        $per_page = 10;
        $page = is_numeric($page) ? $page : 1;
        $offset = ($page - 1) * $per_page;

        $total_alarms = $this->Box_Model->count_alarm_logs_by_box($id_box);
        init_pagination(site_url("admin/alarme_box/$id_box"), $total_alarms, $per_page, 4);

        $data['alarms'] = $this->Box_Model->get_alarm_logs_by_box_paginated($id_box, $per_page, $offset);
        $data['pagination_links'] = $this->pagination->create_links();
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