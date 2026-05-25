<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class User_model extends CI_Model {

    public function __construct() {
        parent::__construct();
        $this->load->database();
    }

    public function login($username, $password) {
        $this->db->where('username', $username);
        $this->db->or_where('email', $username);
        $query = $this->db->get('users');
        
        log_message('debug', 'عدد النتائج: ' . $query->num_rows());
        
        $user = $query->row();

        if ($user) {
            log_message('debug', 'تم العثور على المستخدم: ' . $user->username);
            log_message('debug', 'كلمة المرور في DB: ' . $user->password);
            
            if (password_verify($password, $user->password)) {
                log_message('debug', 'كلمة المرور صحيحة!');
                return $user;
            } else {
                log_message('debug', 'كلمة المرور غير صحيحة');
            }
        } else {
            log_message('debug', 'لم يتم العثور على المستخدم');
        }
        
        return false;
    }
}