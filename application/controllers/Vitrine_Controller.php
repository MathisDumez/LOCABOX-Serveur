<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Vitrine_Controller extends CI_Controller {
    public function __construct() {
        parent::__construct();
        $this->load->model('Main_Model');
    }

    public function index() {
        $data['resultats'] = $this->Main_Model->getBoxData();
        $this->load->view('vitrine_box.php', $data);
    }
}
?>