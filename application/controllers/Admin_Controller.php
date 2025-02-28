<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Admin_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Admin_Model');
        $this->load->library(['session', 'form_validation']);
    }

    private function check_admin() {
        if (!$this->session->userdata('admin')) {
            $this->session->set_flashdata('error', 'Accès réservé aux administrateurs.');
            redirect('Vitrine_Controller/index');
        }
    }

    public function dashboard() {
        $this->check_admin();
        $data['users'] = $this->Admin_Model->get_all_users();
        $data['boxes'] = $this->Admin_Model->get_all_boxes();
        $this->load->view('dashboard_admin', $data);
    }

    public function update_user($id_user_box) {
        $this->check_admin();

        $this->form_validation->set_rules('email', 'Email', 'required|valid_email');
        if ($this->form_validation->run() === FALSE) {
            $this->session->set_flashdata('error', validation_errors());
            redirect('Admin_Controller/dashboard');
        }

        $data = ['email' => $this->input->post('email'), 'admin' => $this->input->post('admin')];
        $this->Admin_Model->update_user($id_user_box, $data);
        $this->session->set_flashdata('success', 'Utilisateur mis à jour.');
        redirect('Admin_Controller/dashboard');
    }

    public function delete_user($id_user_box) {
        $this->check_admin();
        $this->Admin_Model->delete_user($id_user_box);
        $this->session->set_flashdata('success', 'Utilisateur supprimé.');
        redirect('Admin_Controller/dashboard');
    }

    public function update_box($id_box) {
        $this->check_admin();
        $data = [
            'size' => $this->input->post('size'),
            'available' => $this->input->post('available')
        ];
        $this->Admin_Model->update_box($id_box, $data);
        $this->session->set_flashdata('success', 'Box mis à jour.');
        redirect('Admin_Controller/dashboard');
    }
}
?>