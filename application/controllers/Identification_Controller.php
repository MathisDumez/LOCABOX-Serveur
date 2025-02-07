<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Identification_Controller extends CI_Controller {
    
    protected $user;
    
    public function __construct() {
        parent::__construct();
        $this->load->model('User_Model');
        $this->user = $this->session->userdata('user'); // Récupérer l'utilisateur en session
    }

    protected function check_auth($role) {
        if (!$this->user || $this->user['role'] !== $role) {
            redirect('login');
        }
    }
}
?>