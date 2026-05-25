<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Invoices extends CI_Controller {

    public function __construct() {
        parent::__construct();
        
        if(!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }
        
        $this->load->model('Invoice_model');
        $this->load->model('Product_model');
        $this->load->model('Stock_model');
        
        $this->load->helper('form');
        $this->load->library('form_validation');
    }

    public function create() {
        $data['title'] = 'إنشاء فاتورة جديدة';
        $data['active_menu'] = 'invoices';
        

        $data['customers'] = $this->Invoice_model->get_all_customers();
        
        if($this->session->userdata('role') == 'admin') {
            $data['warehouses'] = $this->db->get('warehouses')->result();
        } else {
            $user_warehouse_id = $this->session->userdata('warehouse_id');
            $this->db->where('id', $user_warehouse_id);
            $data['warehouses'] = $this->db->get('warehouses')->result();
        }
        

        $data['invoice_no'] = $this->Invoice_model->generate_invoice_number();
        
        $this->load->view('templates/header', $data);
        $this->load->view('invoices/create', $data);
        $this->load->view('templates/footer');
    }

    public function search_products() {
        $warehouse_id = $this->input->post('warehouse_id');
        $search = $this->input->post('search');
        
        $products = $this->Invoice_model->search_products($search, $warehouse_id);
        echo json_encode($products);
    }

    public function save_invoice() {
        if($this->input->post()) {
            $customer_id = $this->input->post('customer_id');
            $warehouse_id = $this->input->post('warehouse_id');
            $invoice_no = $this->input->post('invoice_no');
            $items = json_decode($this->input->post('items'), true);
            $discount_percent = $this->input->post('discount_percent') ?: 0;
            
            if(empty($items)) {
                echo json_encode(['success' => false, 'message' => 'لم تتم إضافة أي منتجات']);
                return;
            }
            
            foreach($items as $item) {
                $current_stock = $this->Stock_model->get_quantity($item['product_id'], $warehouse_id);
                if($current_stock < $item['quantity']) {
                    echo json_encode(['success' => false, 'message' => 'الكمية غير كافية للمنتج: ' . $item['name']]);
                    return;
                }
            }
            
            $invoice_id = $this->Invoice_model->save_invoice($customer_id, $warehouse_id, $invoice_no, $items, $discount_percent, $this->session->userdata('user_id'));
            
            if($invoice_id) {
                foreach($items as $item) {
                    $this->Stock_model->update_stock($item['product_id'], $warehouse_id, $item['quantity'], 'subtract');
                }
                
                echo json_encode(['success' => true, 'message' => 'تم حفظ الفاتورة بنجاح', 'invoice_id' => $invoice_id]);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ في حفظ الفاتورة']);
            }
        }
    }

    public function index() {
    $data['title'] = 'قائمة الفواتير';
    $data['active_menu'] = 'invoices_list';
    
    if($this->session->userdata('role') == 'admin') {
        $data['invoices'] = $this->Invoice_model->get_all_invoices();
    } else {
        $warehouse_id = $this->session->userdata('warehouse_id');
        $data['invoices'] = $this->Invoice_model->get_invoices_by_warehouse($warehouse_id);
    }
    
    $this->load->view('templates/header', $data);
    $this->load->view('invoices/index', $data);
    $this->load->view('templates/footer');
}

public function view($id) {
    $data['title'] = 'تفاصيل الفاتورة';
    $data['active_menu'] = 'invoices_list';
    
    $data['invoice'] = $this->Invoice_model->get_invoice_by_id($id);
    
    if(!$data['invoice']) {
        show_404();
    }
    
    if($this->session->userdata('role') != 'admin') {
        if($data['invoice']->warehouse_id != $this->session->userdata('warehouse_id')) {
            show_error('ليس لديك صلاحية للوصول إلى هذه الفاتورة', 403);
        }
    }
    
    $data['items'] = $this->Invoice_model->get_invoice_items($id);
    
    $this->load->view('templates/header', $data);
    $this->load->view('invoices/view', $data);
    $this->load->view('templates/footer');
}

public function delete($id) {
    if($this->session->userdata('role') != 'admin') {
        echo json_encode(['success' => false, 'message' => 'ليس لديك صلاحية']);
        return;
    }
    
    if($this->Invoice_model->delete_invoice($id)) {
        echo json_encode(['success' => true, 'message' => 'تم حذف الفاتورة بنجاح']);
    } else {
        echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
    }
}

}