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

    public function gestion_client() {
        $this->check_admin(); // Vérifie si l'utilisateur est admin
    
        $data['user_box'] = $this->Client_Model->get_all_users(); // Récupère la liste des clients
        
        $this->load->view('gestion_client', $data);
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
    
        if ($this->Client_Model->has_rents($id_user_box)) {
            $this->session->set_flashdata('error', 'Impossible de supprimer ce client : des réservations existent.');
            redirect('admin/gestion_client');
            return;
        }
    
        $this->Client_Model->delete_user($id_user_box);
        $this->session->set_flashdata('success', 'Utilisateur supprimé.');
        redirect('admin/gestion_client');
    }    

    // Formulaire d'ajout de client
    public function form_add_client() {
        $this->check_admin();
        $this->load->view('ajouter_client');
    }

    // Insérer un nouveau client
    public function insert_client() {
        $this->check_admin();

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        $this->form_validation->set_rules('password', 'Mot de passe', 'required|min_length[6]');

        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('admin/ajouter_client');
        }

        $data = [
            'email' => $this->input->post('email'),
            'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
            'admin' => $this->input->post('admin'),
            'level' => 1, // Par défaut
            'fcm' => ''
        ];

        $this->Client_Model->insert('user_box', $data);

        $this->session->set_flashdata('success', 'Nouveau client ajouté.');
        redirect('admin/gestion_client');
    }

    // Formulaire de modification d'un client
    public function form_update_client($id_user_box) {
        $this->check_admin();

        $user = $this->Client_Model->get_by_id('user_box', $id_user_box, 'id_user_box');
        if (!$user) {
            $this->session->set_flashdata('error', 'Utilisateur introuvable.');
            redirect('admin/gestion_client');
        }

        $data['user'] = $user;
        $this->load->view('modifier_client', $data);
    }
}
?>