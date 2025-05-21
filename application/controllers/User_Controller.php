<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->library(['session', 'form_validation']);
    }

    private function check_auth() {
        // Vérifier si l'utilisateur est authentifié
        if (!$this->session->userdata('id_user_box')) {
            // Vérifier si la redirection vers 'identification' est déjà en cours
            if ($this->uri->segment(1) != 'identification') {
                $this->session->set_flashdata('error', 'Veuillez vous connecter.');
                redirect('id/identification');
            }
        }
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }

    public function dashboard_user($page = 1) {
        $this->check_auth();

        // Mettre à jour les statuts des réservations
        $this->User_Model->update_reservation_status();

        $per_page = 10;
        $offset = ($page - 1) * $per_page;

        // Récupération des filtres GET
        $size = $this->input->get('size');
        $warehouse = $this->input->get('warehouse');
        $status = $this->input->get('status');

        // Préparer les paramètres de requête pour le suffixe URL
        $query_params = [
            'size' => $size,
            'warehouse' => $warehouse,
            'status' => $status
        ];

        // Compter le total des réservations filtrées pour l'utilisateur
        $total_reservations = $this->User_Model->count_filtered_reservations(
            $this->session->userdata('id_user_box'),
            $size,
            $warehouse,
            $status
        );

        // Récupérer les réservations paginées
        $reservations = $this->User_Model->get_paginated_reservations(
            $this->session->userdata('id_user_box'),
            $per_page,
            $offset,
            $size,
            $warehouse,
            $status
        );

        // Charger les entrepôts et statuts pour les filtres
        $data['warehouses'] = $this->User_Model->get_all('warehouse');
        $data['status'] = $this->User_Model->get_distinct_status();

        // Charger les réservations et filtres dans la vue
        $data['reservations'] = $reservations;
        $data['size_filter'] = $size;
        $data['warehouse_filter'] = $warehouse;
        $data['status_filter'] = $status;

        // Pagination helper
        $this->load->helper('pagination_helper');
        init_pagination(site_url('user/dashboard'), $total_reservations, $per_page, 3, $query_params);
        $data['pagination_links'] = $this->pagination->create_links();

        $this->load->view('dashboard_user', $data);
    }

    public function dashboard_admin() {
        $this->check_admin();
        $this->load->view('dashboard_admin');
    }

    public function reserver() {
        $this->check_auth();
    
        $box_id = $this->input->get('box_id');
        if (!$box_id) {
            $this->session->set_flashdata('error', 'Aucun box sélectionné.');
            redirect('vitrine/index');
        }
    
        // Récupération des infos du box
        $box = $this->User_Model->get_by_id('box', $box_id, 'id_box');
        if (!$box) {
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('vitrine/index');
        }
    
        $data['reservation'] = [
            'box_id' => $box->id_box,
            'box_num' => $box->num,
            'box_size' => $box->size,
            'warehouse_name' => $this->User_Model->get_by_id('warehouse', $box->id_warehouse, 'id_warehouse')->name ?? 'Indisponible'
        ];
    
        $this->load->view('reserver', $data);
    }
    
    public function valider_reservation() {
        $this->check_auth();

        $box_id = $this->input->post('box_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        if (!$box_id || !$start_date || !$end_date) {
            $this->session->set_flashdata('error', 'Informations de réservation incomplètes.');
            redirect('user/reserver');
        }

        // Conversion des dates/heure au format MySQL
        $start_datetime = date('Y-m-d H:i:s', strtotime($start_date));
        $end_datetime = date('Y-m-d H:i:s', strtotime($end_date));

        $data = [
            'id_user_box' => $this->session->userdata('id_user_box'),
            'id_box' => $box_id,
            'start_reservation_date' => $start_datetime,
            'end_reservation_date' => $end_datetime,
            'status' => 'En Attente'
        ];

        if ($this->db->insert('rent', $data)) {
            $this->session->set_flashdata('success', 'Réservation enregistrée avec succès.');
            redirect('user/dashboard');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la réservation.');
            redirect('user/reserver');
        }
    }

    public function changement_mdp() {
        $this->check_auth();
        $this->load->view('changement_mdp');
    }

    public function update_password() {
        $this->check_auth();

        if ($this->input->post('new_password') !== $this->input->post('confirm_password')) {
            $this->session->set_flashdata('error', 'Les mots de passe ne correspondent pas.');
            redirect('user/change_password');
        }
        $this->form_validation->set_rules('new_password', 'Mot de passe', 'required|min_length[8]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            redirect('user/change_password');
        }

        // Mise à jour du mot de passe
        $update_status = $this->User_Model->update_password($this->session->userdata('id_user_box'), $this->input->post('new_password'));
        
        if ($update_status['status']) {
            $this->session->set_flashdata('success', $update_status['message']);
        } else {
            $this->session->set_flashdata('error', $update_status['message']);
        }

        redirect('user/dashboard');
    }
    
    public function annuler_reservation($rent_number) {
        $this->check_auth();
    
        $id_user_box = $this->session->userdata('id_user_box');
    
        if ($this->User_Model->cancel_reservation($rent_number, $id_user_box)) {
            $this->session->set_flashdata('success', 'Réservation annulée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Impossible d\'annuler la réservation.');
        }
    
        redirect('user/dashboard');
    }    
}
?>
