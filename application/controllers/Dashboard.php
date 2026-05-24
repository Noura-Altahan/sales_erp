<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Dashboard extends CI_Controller {

    public function __construct() {
        parent::__construct();
        if(!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        $this->load->database();
    }

    public function stats() {
        $data['products'] = $this->db->count_all('products');
        $data['invoices'] = $this->db->count_all('invoices');
        $data['warehouses'] = $this->db->count_all('warehouses');
        $data['customers'] = $this->db->count_all('customers');
        
        echo json_encode($data);
    }
}