<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Auth extends CI_Controller {

    public function __construct() {
        parent::__construct();
        $this->load->helper('url'); 
        $this->load->model('User_model');
        $this->load->database();
    }

    public function login() {
        if ($this->session->userdata('logged_in')) {
            redirect('welcome');
        }

        $data['error'] = '';
        
        if ($this->input->post()) {
            $username = $this->input->post('username');
            $password = $this->input->post('password');
            
            $user = $this->User_model->login($username, $password);
            
            if ($user) {
                $this->session->set_userdata([
                    'user_id' => $user->id,
                    'username' => $user->username,
                    'role' => $user->role,
                    'warehouse_id' => $user->warehouse_id,
                    'logged_in' => TRUE
                ]);
                redirect('welcome');
            } else {
                $data['error'] = 'اسم المستخدم أو كلمة المرور غير صحيحة';
            }
        }
        
        $this->load->view('auth/login', $data);
    }

    public function logout() {
        $this->session->sess_destroy();
        redirect('auth/login');
    }
}