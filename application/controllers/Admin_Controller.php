<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
class Admin_Controller extends Identification_Controller {
    
    public function __construct() {
        parent::__construct();
        $this->check_auth('admin'); // Seuls les admins peuvent y accéder
    }
}
?>