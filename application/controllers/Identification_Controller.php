<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Identification_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Identification_Model');
        $this->load->library('session');
    }

    // Afficher la page d'identification
    public function identification() {
        // Rediriger si déjà connecté
        if ($this->session->userdata('id_user_box')) {
            $redirect_url = $this->session->userdata('redirect_url') ?? 'User_Controller/dashboard';
            $this->session->unset_userdata('redirect_url');
            redirect($redirect_url);
        }

        $this->load->view('identification');
    }

    // Gérer la connexion utilisateur
    public function login() {
        $email = trim($this->input->post('email', true));
        $password = $this->input->post('password');

        log_message('debug', 'Tentative de connexion pour : ' . $email);

        // Vérifier l'email et le mot de passe
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Format d\'email invalide.');
            redirect('Identification_Controller/identification');
        }

        if (empty($password)) {
            $this->session->set_flashdata('error', 'Le mot de passe est requis.');
            redirect('Identification_Controller/identification');
        }

        // Vérifier l'utilisateur
        $user = $this->Identification_Model->check_user($email, $password);

        if ($user) {
            log_message('debug', 'Connexion réussie pour : ' . $email);

            // Stocker les informations utilisateur
            $this->session->set_userdata([
                'id_user_box' => $user->id_user_box,
                'email' => $user->email,
                'admin' => $user->admin
            ]);

            // Si une réservation est en attente, rediriger vers sa validation
            if ($this->session->userdata('reservation_temp')) {
                $this->session->set_userdata('reservation_data', $this->session->userdata('reservation_temp'));
                $this->session->unset_userdata('reservation_temp');
                redirect('User_Controller/valider_reservation');
            }

            // Redirection après connexion
            $redirect_url = $this->session->userdata('redirect_url') ?? 'Vitrine_Controller/index';
            $this->session->unset_userdata('redirect_url');
            redirect($redirect_url);
        } else {
            log_message('error', 'Échec de connexion pour : ' . $email);
            $this->session->set_flashdata('error', 'Email ou mot de passe incorrect.');
            redirect('Identification_Controller/identification');
        }
    }

    // Déconnexion
    public function logout() {
        $this->session->sess_destroy();
        redirect('Vitrine_Controller/index');
    }

    // Afficher la page d'inscription client
    public function inscription_client() {
        if ($this->session->userdata('id_user_box')) {
            redirect('Vitrine_Controller/index');
        }
        $this->load->view('inscription_client');
    }

    // Gérer l'inscription client
    public function register() {
        $email = trim($this->input->post('email', true));
        $password = $this->input->post('password');

        log_message('debug', 'Tentative d\'inscription pour : ' . $email);

        // Vérifications des champs
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->session->set_flashdata('error', 'Format d\'email invalide.');
            redirect('Identification_Controller/inscription_client');
        }

        if (empty($password) || strlen($password) < 6) {
            $this->session->set_flashdata('error', 'Le mot de passe doit contenir au moins 6 caractères.');
            redirect('Identification_Controller/inscription_client');
        }

        // Vérifier si l'email existe déjà
        if ($this->Identification_Model->check_email_exists($email)) {
            $this->session->set_flashdata('error', 'Cet email est déjà utilisé.');
            redirect('Identification_Controller/inscription_client');
        }

        // Hachage du mot de passe et enregistrement
        $data = [
            'email' => htmlspecialchars($email),
            'password' => password_hash($password, PASSWORD_ARGON2I),
            'admin' => 0,
            'level' => 1
        ];

        if ($this->Identification_Model->register_client($data)) {
            log_message('debug', 'Inscription réussie pour : ' . $email);
            $this->session->set_flashdata('success', 'Inscription réussie, veuillez vous connecter.');
            redirect('Identification_Controller/identification');
        } else {
            log_message('error', 'Échec de l\'inscription pour : ' . $email);
            $this->session->set_flashdata('error', 'Erreur lors de l\'inscription.');
            redirect('Identification_Controller/inscription_client');
        }
    }
}
?>