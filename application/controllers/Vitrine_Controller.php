<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Vitrine_Controller extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->model('Vitrine_Model');
        $this->load->helper('url');
        $this->load->library('session');
    }

    // Page principale avec tri des box
    public function index() {
        $sort = $this->input->get('sort'); 
        $data['boxes'] = $this->Vitrine_Model->get_sorted_boxes($sort);
        $this->load->view('vitrine_box', $data);
    }

    // Détails d'un box
    public function detail($id) {
        $id = (int) $id; // Sécurisation de l'ID

        if ($id <= 0 || !($data['box'] = $this->Vitrine_Model->get_box_details($id))) {
            log_message('error', 'Tentative d\'accès à un box inexistant ou ID invalide : ' . $id);
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('Vitrine_Controller/index');
        }

        log_message('debug', 'Détails du box affichés : ' . print_r($data['box'], true));
        $this->load->view('page_box', $data);
    }

    // Gérer une réservation
    public function reserver() {
        $box_id = (int) $this->input->post('box_id');
        $start_date = $this->input->post('start_date');
        $end_date = $this->input->post('end_date');

        // Vérification de l'ID du box
        if ($box_id <= 0) {
            log_message('error', 'Réservation avec un ID de box invalide : ' . $box_id);
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('Vitrine_Controller/index');
        }

        // Vérification si le box existe
        if (!($box = $this->Vitrine_Model->get_box_details($box_id))) {
            log_message('error', 'Tentative de réservation pour un box inexistant : ' . $box_id);
            $this->session->set_flashdata('error', 'Box introuvable.');
            redirect('Vitrine_Controller/index');
        }

        // Vérification des dates
        if (!$this->is_valid_date($start_date) || !$this->is_valid_date($end_date) || $start_date >= $end_date) {
            log_message('error', 'Dates invalides pour la réservation du box ' . $box_id);
            $this->session->set_flashdata('error', 'Les dates de réservation sont invalides.');
            redirect('Vitrine_Controller/detail/' . $box_id);
        }

        // Vérification de la disponibilité
        if (!$this->Vitrine_Model->is_box_available($box_id, $start_date, $end_date)) {
            log_message('error', 'Box déjà réservé : ' . $box_id);
            $this->session->set_flashdata('error', 'Ce box est déjà réservé sur cette période.');
            redirect('Vitrine_Controller/detail/' . $box_id);
        }

        // Vérification si l'utilisateur est connecté
        if (!$this->session->userdata('id_user_box')) {
            // Stockage temporaire des infos de réservation
            $this->session->set_userdata('reservation_temp', compact('box_id', 'start_date', 'end_date'));

            // Stockage de l'URL précédente pour redirection après connexion
            $this->session->set_userdata('redirect_url', site_url('Vitrine_Controller/detail/' . $box_id));

            // Redirection vers la connexion
            redirect('Identification_Controller/identification');
        }

        // Si l'utilisateur est connecté, passer directement à la validation
        $this->session->set_userdata('reservation_data', compact('box_id', 'start_date', 'end_date'));
        redirect('User_Controller/valider_reservation');
    }    

    // Vérification de format de date
    private function is_valid_date($date) {
        $d = DateTime::createFromFormat('Y-m-d', $date);
        return $d && $d->format('Y-m-d') === $date;
    }
}
?>