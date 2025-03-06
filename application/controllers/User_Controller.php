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
                redirect('identification');
            }
        }
    }

    public function reserver() {
        $this->check_auth();
        $data['reservation'] = $this->session->userdata('reservation_data');
        $this->load->view('reserver', $data);
    }

    public function valider_reservation() {
        $this->check_auth();
        $reservation_data = $this->session->userdata('reservation_data');

        if (!$reservation_data) {
            $this->session->set_flashdata('error', 'Aucune réservation en attente.');
            redirect('Vitrine_Controller/index');
        }

        $data = [
            'id_user_box' => $this->session->userdata('id_user_box'),
            'id_box' => $reservation_data['box_id'],
            'start_reservation_date' => $reservation_data['start_date'],
            'end_reservation_date' => $reservation_data['end_date'],
            'status' => 'confirmé'
        ];

        if ($this->db->insert('rent', $data)) {
            $this->session->set_flashdata('success', 'Réservation confirmée !');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la réservation.');
        }

        $this->session->unset_userdata('reservation_data');
        redirect('user/reserver');
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

    public function dashboard_user() {
        $this->check_auth();
    
        // Charger les réservations de l'utilisateur connecté
        $data['reservations'] = $this->User_Model->get_reservations($this->session->userdata('id_user_box'));
    
        // Charger la vue
        $this->load->view('dashboard_user', $data);
    }
}
?>
