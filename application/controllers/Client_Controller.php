<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Client_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Client_Model');
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
    
        $data['boxes'] = $this->Client_Model->get_all_boxes(); // Récupère la liste des box
        $data['warehouses'] = $this->Client_Model->get_all_warehouses(); // Récupère la liste des bâtiments
        
        $this->load->view('etat_box', $data); // Charge la vue etat_box.php
    }
    

    public function update_user($id_user_box) {
        $this->check_admin();

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/dashboard');
        }

        $data = ['email' => $this->input->post('email'), 'admin' => $this->input->post('admin')];
        $this->Client_Model->update_user($id_user_box, $data);
        $this->session->set_flashdata('success', 'Utilisateur mis à jour.');
        redirect('admin/dashboard');
    }

    public function delete_user($id_user_box) {
        $this->check_admin();
        $this->Client_Model->delete_user($id_user_box);
        $this->session->set_flashdata('success', 'Utilisateur supprimé.');
        redirect('Admin_Controller/dashboard');
    }

    public function update_box($id_box) {
        $this->check_admin();
        $data = [
            'size' => $this->input->post('size'),
            'available' => $this->input->post('available')
        ];
        $this->Client_Model->update_box($id_box, $data);
        $this->session->set_flashdata('success', 'Box mis à jour.');
        redirect('admin/dashboard');
    }

    public function acces_box($id_box) {
        $this->check_admin(); // Vérifie que l'utilisateur est admin
        $data['access_logs'] = $this->Client_Model->get_access_logs_by_box($id_box);
        $this->load->view('acces_box', $data);
    }
    
    public function alarme_box($id_box) {
        $this->check_admin(); // Vérifie que l'utilisateur est admin
        $data['alarms'] = $this->Client_Model->get_alarm_logs_by_box($id_box);
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
        $warehouse = $this->Client_Model->get_by_id('warehouse', $id_warehouse, 'id_warehouse');
        if (!$warehouse) {
            $this->session->set_flashdata('error', 'Le bâtiment sélectionné n\'existe pas.');
            redirect('admin/etat_box');
        }
    
        // Vérification qu'il n'existe pas déjà un box avec le même numéro dans ce bâtiment
        $existing_box = $this->Client_Model->get_where('box', ['num' => $num, 'id_warehouse' => $id_warehouse]);
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
    
        if ($this->Client_Model->ajouter_box($data)) {
            $this->session->set_flashdata('success', 'Box ajouté avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'ajout du box.');
        }
    
        redirect('admin/etat_box');
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
        $data['reservations'] = $this->Client_Model->get_all_reservations($filters);
    
        // Récupérer les entrepôts et statuts existants pour les listes déroulantes
        $data['warehouses'] = $this->Client_Model->get_all_warehouses();
        $data['status'] = $this->db->select('DISTINCT(status)')->get('rent')->result();
    
        $this->load->view('gestion_reservation', $data);
    }
    
    public function modifier_reservation($rent_number) {
        $this->check_admin();
    
        $this->form_validation->set_rules('start_reservation_date', 'Date de début', 'required');
        $this->form_validation->set_rules('end_reservation_date', 'Date de fin', 'required');
        $this->form_validation->set_rules('status', 'Statut', 'required|in_list[En Attente,En Cours,Terminée,Annulée]');
    
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/gestion_reservation');
        }
    
        $data = [
            'start_reservation_date' => $this->input->post('start_reservation_date'),
            'end_reservation_date' => $this->input->post('end_reservation_date'),
            'status' => $this->input->post('status')
        ];
    
        if ($this->Client_Model->update_reservation($rent_number, $data)) {
            $this->session->set_flashdata('success', 'Réservation modifiée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la modification.');
        }
    
        redirect('admin/gestion_reservation');
    }        

    public function valider_reservation($rent_number) {
        $this->check_admin();
    
        $reservation = $this->Client_Model->get_by_id('rent', $rent_number, 'rent_number');
        if (!$reservation || $reservation->status !== 'En Attente') {
            $this->session->set_flashdata('error', 'Impossible de valider cette réservation.');
            redirect('admin/gestion_reservation');
        }
    
        if ($this->Client_Model->valider_reservation($rent_number)) {
            $this->session->set_flashdata('success', 'Réservation validée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la validation.');
        }
    
        redirect('admin/gestion_reservation');
    }
    
    public function annuler_reservation($rent_number) {
        $this->check_admin();
    
        $reservation = $this->Client_Model->get_by_id('rent', $rent_number, 'rent_number');
        if (!$reservation || $reservation->status !== 'En Attente') {
            $this->session->set_flashdata('error', 'Impossible d\'annuler cette réservation.');
            redirect('admin/gestion_reservation');
        }
    
        if ($this->Client_Model->update_reservation($rent_number, ['status' => 'Annulée'])) {
            $this->session->set_flashdata('success', 'Réservation annulée avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'annulation.');
        }
    
        redirect('admin/gestion_reservation');
    }        
}
?>