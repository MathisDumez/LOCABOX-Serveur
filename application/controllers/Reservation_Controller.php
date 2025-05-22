<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservation_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Reservation_Model');
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }
    
    public function gestion_reservation() {
        $this->check_admin();

        $this->Reservation_Model->update_reservation_status();

        // Récupérer les filtres GET
        $filters = [
            'email' => $this->input->get('email'),
            'size' => $this->input->get('size'),
            'warehouse' => $this->input->get('warehouse'),
            'status' => $this->input->get('status'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
            'box_num' => $this->input->get('box_num'),
        ];

        // Charger le helper pagination
        $this->load->helper('pagination_helper');

        // Nombre total d'éléments filtrés
        $total_rows = $this->Reservation_Model->count_reservations_filtered($filters);

        // Nombre d'éléments par page
        $per_page = 10;

        // Numéro de page actuel (base 1)
        $page = (int) $this->input->get('page');
        if ($page < 1) $page = 1;

        // Calcul offset pour la requête SQL (base 0)
        $offset = ($page - 1) * $per_page;

        // Récupérer les données paginées
        $data['reservations'] = $this->Reservation_Model->get_reservations_paginated($per_page, $offset, $filters);

        // Préparer URL de base pour la pagination (sans page)
        $base_url = site_url('admin/gestion_reservation');

        // Garder les filtres pour passer en query string
        $query_params = $_GET;
        unset($query_params['page']); // on gère la page à part

        // Initialiser la pagination
        init_pagination($base_url, $total_rows, $per_page, 3, $query_params);

        // Passer la pagination au template
        $data['pagination_links'] = $this->pagination->create_links();

        $data['warehouses'] = $this->Reservation_Model->get_all_warehouses();
        $data['status'] = $this->db->select('DISTINCT(status)')->get('rent')->result();

        $this->load->view('gestion_reservation', $data);
    }

    public function detail_reservation($rent_number) {
        $this->check_admin();

        $this->db->select('
            rent.*, 
            user_box.email,
            box.num, box.size, box.available, box.id_box,
            warehouse.name AS warehouse_name
        ');
        $this->db->from('rent');
        $this->db->join('user_box', 'rent.id_user_box = user_box.id_user_box', 'left');
        $this->db->join('box', 'rent.id_box = box.id_box', 'left');
        $this->db->join('warehouse', 'box.id_warehouse = warehouse.id_warehouse', 'left');
        $this->db->where('rent.rent_number', $rent_number);
        $reservation = $this->db->get()->row();

        if (!$reservation) {
            $this->session->set_flashdata('error', 'Réservation introuvable.');
            redirect('admin/gestion_reservation');
        }

        $data['reservation'] = $reservation;
        $this->load->view('detail_reservation', $data);
    }
    
    public function modifier_reservation($rent_number) {
        $this->check_admin();
    
        // Récupérer la réservation
        $data['reservation'] = $this->Reservation_Model->get_by_id('rent', $rent_number, 'rent_number');
    
        if (!$data['reservation']) {
            $this->session->set_flashdata('error', 'Réservation introuvable.');
            redirect('admin/gestion_reservation');
        }
    
        // Si le formulaire a été soumis
        if ($this->input->server('REQUEST_METHOD') === 'POST') {
            $this->form_validation->set_rules('start_reservation_date', 'Date de début', 'required');
            $this->form_validation->set_rules('end_reservation_date', 'Date de fin', 'required');
            $this->form_validation->set_rules('status', 'Statut', 'required|in_list[En Attente,En Cours,Validée,Terminée,Annulée]');
    
            if ($this->form_validation->run() === TRUE) {
                $update_data = [
                    'start_reservation_date' => date('Y-m-d H:i:s', strtotime($this->input->post('start_reservation_date'))),
                    'end_reservation_date' => date('Y-m-d H:i:s', strtotime($this->input->post('end_reservation_date'))),
                    'status' => $this->input->post('status'),
                ];
    
                if ($this->Reservation_Model->update_reservation($rent_number, $update_data)) {
                    $this->session->set_flashdata('success', 'Réservation modifiée avec succès.');
                    redirect('admin/gestion_reservation');
                } else {
                    $this->session->set_flashdata('error', 'Erreur lors de la modification.');
                }
            }
        }
    
        // Charger la vue avec les données de la réservation
        $this->load->view('modifier_reservation', $data);
    }           

    public function valider_reservation($rent_number) {
        $this->check_admin();
    
        $reservation = $this->Reservation_Model->get_by_id('rent', $rent_number, 'rent_number');
        if (!$reservation || $reservation->status !== 'En Attente') {
            $this->session->set_flashdata('error', 'Impossible de valider cette réservation.');
            redirect('admin/gestion_reservation');
        }
    
        if ($this->Reservation_Model->valider_reservation($rent_number)) {
            $this->session->set_flashdata('success', 'Réservation validée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la validation.');
        }
    
        redirect('admin/gestion_reservation');
    }
    
    public function annuler_reservation($rent_number) {
        $this->check_admin();
    
        $reservation = $this->Reservation_Model->get_by_id('rent', $rent_number, 'rent_number');
        if (!$reservation || $reservation->status !== 'En Attente') {
            $this->session->set_flashdata('error', 'Impossible d\'annuler cette réservation.');
            redirect('admin/gestion_reservation');
        }
    
        if ($this->Reservation_Model->update_reservation($rent_number, ['status' => 'Annulée'])) {
            $this->session->set_flashdata('success', 'Réservation annulée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'annulation.');
        }
    
        redirect('admin/gestion_reservation');
    }

    public function supprimer_reservation($rent_number) {
        $this->check_admin();

        // Récupérer la réservation
        $reservation = $this->Reservation_Model->get_by_id('rent', $rent_number, 'rent_number');

        // Vérifie que la réservation existe
        if (!$reservation) {
            $this->session->set_flashdata('error', 'Réservation introuvable.');
            redirect('admin/gestion_reservation');
        }

        // Ne permettre la suppression que si la réservation est terminée ou annulée
        if (!in_array($reservation->status, ['Terminée', 'Annulée'])) {
            $this->session->set_flashdata('error', 'Seules les réservations terminées ou annulées peuvent être supprimées.');
            redirect('admin/gestion_reservation');
        }

        // Supprimer la réservation
        if ($this->Reservation_Model->delete('rent', $rent_number, 'rent_number')) {
            $this->session->set_flashdata('success', 'Réservation supprimée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la suppression.');
        }

        redirect('admin/gestion_reservation');
    }
}
?>