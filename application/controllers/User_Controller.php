<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
        $this->load->library('session');
        $this->load->library('form_validation');
    }

    // Vérifier si l'utilisateur est connecté
    private function check_auth() {
        if (!$this->session->userdata('id_user_box')) {
            $this->session->set_flashdata('error', 'Veuillez vous connecter pour accéder à cette page.');
            redirect('Identification_Controller/identification');
        }
    }

    // Page de réservation avec messages
    public function reserver() {
        $this->check_auth();

        // Récupérer les données de réservation si elles existent
        $reservation_data = $this->session->userdata('reservation_data');
        $data['reservation'] = $reservation_data;

        // Charger la vue
        $this->load->view('reserver', $data);
    }

    // Valider la réservation après connexion
    public function valider_reservation() {
        $this->check_auth();

        $reservation_data = $this->session->userdata('reservation_data');
        if (!$reservation_data) {
            $this->session->set_flashdata('error', 'Aucune réservation en attente.');
            redirect('Vitrine_Controller/index');
        }

        $data = [
            'user_id' => $this->session->userdata('id_user_box'),
            'box_id' => $reservation_data['box_id'],
            'start_date' => $reservation_data['start_date'],
            'end_date' => $reservation_data['end_date']
        ];

        if ($this->db->insert('rent', $data)) {
            log_message('info', 'Réservation réussie pour l\'utilisateur ID ' . $data['user_id']);
            $this->session->set_flashdata('success', 'Votre réservation a été confirmée avec succès !');
        } else {
            log_message('error', 'Erreur lors de l\'enregistrement de la réservation pour l\'utilisateur ID ' . $data['user_id']);
            $this->session->set_flashdata('error', 'Erreur lors de la confirmation de la réservation.');
        }

        $this->session->unset_userdata('reservation_data');

        // Rediriger vers la page de réservation (reserver.php) avec le message de succès
        redirect('User_Controller/reserver');
    }

    // Affichage de la page de changement de mot de passe
    public function changement_mdp() {
        $this->check_auth();
        $this->load->view('changement_mdp');
    }

    // Mise à jour du mot de passe
    public function update_password() {
        $this->check_auth();

        $id_user = $this->session->userdata('id_user_box');
        $new_password = $this->input->post('new_password');

        // Validation du mot de passe
        $this->form_validation->set_rules('new_password', 'Mot de passe', 'required|min_length[6]');
        if ($this->form_validation->run() == FALSE) {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            redirect('User_Controller/changement_mdp');
        }

        // Hashage du mot de passe avant stockage
        $hashed_password = password_hash($new_password, PASSWORD_BCRYPT);

        // Mise à jour en base de données
        if ($this->User_Model->update_password($id_user, $hashed_password)) {
            log_message('info', 'Mot de passe mis à jour pour l\'utilisateur ID ' . $id_user);
            $this->session->set_flashdata('success', 'Mot de passe mis à jour avec succès.');
        } else {
            log_message('error', 'Erreur lors de la mise à jour du mot de passe pour l\'utilisateur ID ' . $id_user);
            $this->session->set_flashdata('error', 'Erreur lors de la mise à jour du mot de passe.');
        }

        redirect('User_Controller/changement_mdp');
    }
}
?>
