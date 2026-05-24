<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url');
        
        if(!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
    }

    public function index() {
        $data['title'] = 'لوحة التحكم - ERP Mini';
        $data['active_menu'] = 'dashboard';
        
        $this->load->view('templates/header', $data);
        $this->load->view('welcome_message');
        $this->load->view('templates/footer');
    }
}