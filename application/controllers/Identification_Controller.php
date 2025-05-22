<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Identification_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Identification_Model');
        $this->lang->load('form_validation', 'french');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function identification() {
        if ($this->session->userdata('id_user_box')) {
            redirect('user/dashboard');
        }
        $this->load->view('identification');
    }

    public function inscription() {
        $this->load->view('inscription');
    }

    private function handle_form_validation(string $email, string $password): bool {
        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', strip_tags(validation_errors()));
            return false;
        }
        return true;
    }

    public function process_inscription() {
        if (!$this->handle_form_validation($this->input->post('email'), $this->input->post('password'))) {
            redirect('id/inscription');
        }

        $data = [
            'email' => trim($this->input->post('email')),
            'password' => trim($this->input->post('password')),
            'admin' => 0,
            'level' => 1
        ];

        if ($this->Identification_Model->register_user($data)) {
            $this->session->set_flashdata('success', 'Inscription réussie, vous pouvez vous connecter.');
            redirect('id/identification');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'inscription.');
            redirect('id/inscription');
        }
    }

    public function login() {
        if (!$this->handle_form_validation($this->input->post('email'), $this->input->post('password'))) {
            redirect('id/identification');
        }
    
        $user = $this->Identification_Model->check_user($this->input->post('email'), $this->input->post('password'));
        if ($user) {
            $this->session->set_userdata([
                'id_user_box' => $user->id_user_box,
                'email' => $user->email,
                'admin' => $user->admin
            ]);
            log_message('error', "Utilisateur connecté : " . json_encode($this->session->userdata()));
    
            // Récupération de l'URL de redirection
            $redirect_url = $this->input->post('redirect');
            if (!empty($redirect_url) && filter_var($redirect_url, FILTER_VALIDATE_URL)) {
                redirect($redirect_url);
            } else {
                redirect('vitrine/index');
            }
        }
    
        $this->session->set_flashdata('error', 'Email ou mot de passe incorrect.');
        redirect('id/identification');
    }        

    public function logout() {
        $this->session->sess_destroy();
        redirect('vitrine/index');
    }
}
?>