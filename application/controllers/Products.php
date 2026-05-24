<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Products extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if(!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        $this->load->model('Product_model');
        //$this->load->helper('form');
    }

    public function index() {
        $data['title'] = 'إدارة المنتجات';
        $data['active_menu'] = 'products';
        $data['categories'] = $this->Product_model->get_all_categories();
        
        $this->load->view('templates/header', $data);
        $this->load->view('products/index', $data);
        $this->load->view('templates/footer');
    }

    public function ajax_list() {
        $search = $this->input->get('search');
        $category_id = $this->input->get('category_id');
        $page = $this->input->get('page') ?: 1;
        $per_page = 4;
        
        $products = $this->Product_model->get_products_paginated($search, $category_id, $page, $per_page);
        $total = $this->Product_model->count_products($search, $category_id);
        
        echo json_encode([
            'products' => $products,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $per_page,
            'last_page' => ceil($total / $per_page)
        ]);
    }

    public function add() {
        if($this->input->post()) {
            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost'),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'description' => $this->input->post('description'),
                'is_active' => 1
            );
            
            if($this->Product_model->insert_product($data)) {
                echo json_encode(['success' => true, 'message' => 'تم إضافة المنتج بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ، الرمز موجود مسبقاً']);
            }
        }
    }

    public function get_product($id) {
        $product = $this->Product_model->get_product_by_id($id);
        echo json_encode($product);
    }

    public function edit($id) {
        if($this->input->post()) {
            $data = array(
                'code' => $this->input->post('code'),
                'name' => $this->input->post('name'),
                'category_id' => $this->input->post('category_id'),
                'price' => $this->input->post('price'),
                'cost' => $this->input->post('cost'),
                'alert_quantity' => $this->input->post('alert_quantity'),
                'description' => $this->input->post('description')
            );
            
            if($this->Product_model->update_product($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'تم تحديث المنتج بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
            }
        }
    }

    public function delete($id) {
        if($this->Product_model->delete_product($id)) {
            echo json_encode(['success' => true, 'message' => 'تم حذف المنتج بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
        }
    }

    public function toggle_status($id) {
        $product = $this->Product_model->get_product_by_id($id);
        $new_status = $product->is_active == 1 ? 0 : 1;
        
        if($this->Product_model->update_product($id, ['is_active' => $new_status])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}