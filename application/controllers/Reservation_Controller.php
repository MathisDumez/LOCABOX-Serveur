<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Reservation_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Reservation_Model');
        $this->load->library(['session', 'form_validation']);
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('vitrine/index');
        }
    }       
    
    public function gestion_reservation() {
        $this->check_admin();
    
        // Récupération des filtres via GET
        $filters = [
            'email' => $this->input->get('email'),
            'size' => $this->input->get('size'),
            'warehouse' => $this->input->get('warehouse'),
            'status' => $this->input->get('status'),
            'start_date' => $this->input->get('start_date'),
            'end_date' => $this->input->get('end_date'),
        ];
    
        // Passer les filtres à la requête SQL
        $data['reservations'] = $this->Reservation_Model->get_all_reservations($filters);
    
        // Récupérer les entrepôts et statuts existants pour les listes déroulantes
        $data['warehouses'] = $this->Reservation_Model->get_all_warehouses();
        $data['status'] = $this->db->select('DISTINCT(status)')->get('rent')->result();
    
        $this->load->view('gestion_reservation', $data);
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
            $this->form_validation->set_rules('status', 'Statut', 'required|in_list[En Attente,En Cours,Terminée,Annulée]');
    
            if ($this->form_validation->run() === TRUE) {
                $update_data = [
                    'start_reservation_date' => $this->input->post('start_reservation_date'),
                    'end_reservation_date' => $this->input->post('end_reservation_date'),
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
}
?>