<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->library(['session', 'form_validation']);
    }

    private function check_auth() {
        if (!$this->session->userdata('id_user_box')) {
            $this->session->set_flashdata('error', 'Veuillez vous connecter.');
            redirect('Identification_Controller/identification');
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
        redirect('User_Controller/reserver');
    }

    public function changement_mdp() {
        $this->check_auth();
        $this->load->view('changement_mdp');
    }

    public function update_password() {
        $this->check_auth();

        $this->form_validation->set_rules('new_password', 'Mot de passe', 'required|min_length[8]');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 8 caractères.');
            redirect('User_Controller/changement_mdp');
        }

        if ($this->User_Model->update_password($this->session->userdata('id_user_box'), $this->input->post('new_password'))) {
            $this->session->set_flashdata('success', 'Mot de passe mis à jour avec succès.');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour du mot de passe.');
        }
        redirect('User_Controller/changement_mdp');
    }
}
?>
