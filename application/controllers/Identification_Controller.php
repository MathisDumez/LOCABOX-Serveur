<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Identification_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Identification_Model');
        $this->load->library(['session', 'form_validation']);
        $this->lang->load('form_validation', 'french');
        error_reporting(E_ALL);
        ini_set('display_errors', 1);
    }

    public function identification() {
        if ($this->session->userdata('id_user_box')) {
            redirect('User_Controller/dashboard');
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
            redirect('Identification_Controller/inscription');
        }

        $data = [
            'email' => trim($this->input->post('email')),
            'password' => trim($this->input->post('password')),
            'admin' => 0,
            'level' => 1
        ];

        if ($this->Identification_Model->register_user($data)) {
            $this->session->set_flashdata('success', 'Inscription réussie, vous pouvez vous connecter.');
            redirect('Identification_Controller/identification');
        } else {
            $this->session->set_flashdata('error', 'Erreur lors de l\'inscription.');
            redirect('Identification_Controller/inscription');
        }
    }

    public function login() {
        if (!$this->handle_form_validation($this->input->post('email'), $this->input->post('password'))) {
            redirect('Identification_Controller/identification');
        }

        $user = $this->Identification_Model->check_user($this->input->post('email'), $this->input->post('password'));
        if ($user) {
            $this->session->set_userdata([
                'id_user_box' => $user->id_user_box,
                'email' => $user->email,
                'admin' => $user->admin
            ]);
            log_message('error', "Utilisateur connecté : " . json_encode($this->session->userdata()));
            redirect('Vitrine_Controller/index');
        }
        
        $this->session->set_flashdata('error', 'Email ou mot de passe incorrect.');
        redirect('Identification_Controller/identification');
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('Vitrine_Controller/index');
    }
}
?>