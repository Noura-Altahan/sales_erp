<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $this->load->model('Stock_model');
        $this->load->model('Product_model');
        $this->load->model('Warehouse_model');
    }

    public function index()
    {
        $data['title'] = 'إدارة المخزون';
        $data['active_menu'] = 'stock';

        $data['products'] = $this->Product_model->get_all_products();

        if ($this->session->userdata('role') == 'admin') {
            $data['warehouses'] = $this->Warehouse_model->get_all_warehouses();
        } else {
            $user_warehouse_id = $this->session->userdata('warehouse_id');
            $data['warehouses'] = $this->Warehouse_model->get_warehouse_by_id($user_warehouse_id);
            if ($data['warehouses']) {
                $data['warehouses'] = [$data['warehouses']];
            } else {
                $data['warehouses'] = [];
            }
        }

        $this->load->view('templates/header', $data);
        $this->load->view('stock/index', $data);
        $this->load->view('templates/footer');
    }

    public function ajax_stock_list()
    {
        $product_id = $this->input->get('product_id');
        $warehouse_id = $this->input->get('warehouse_id');

        if ($this->session->userdata('role') != 'admin') {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $stock = $this->Stock_model->get_stock_list($product_id, $warehouse_id);
        echo json_encode($stock);
    }

    public function update_stock()
    {
        if ($this->input->post()) {
            $product_id = $this->input->post('product_id');
            $warehouse_id = $this->input->post('warehouse_id');
            $quantity = $this->input->post('quantity');
            $operation = $this->input->post('operation'); // 'add' or 'subtract'

            if ($this->session->userdata('role') != 'admin') {
                if ($warehouse_id != $this->session->userdata('warehouse_id')) {
                    echo json_encode(['success' => false, 'message' => 'ليس لديك صلاحية لهذا المستودع']);
                    return;
                }
            }

            if ($operation == 'subtract' && $quantity > $this->Stock_model->get_quantity($product_id, $warehouse_id)) {
                echo json_encode(['success' => false, 'message' => 'الكمية المطلوبة أكبر من المتاحة']);
                return;
            }

            if ($this->Stock_model->update_stock($product_id, $warehouse_id, $quantity, $operation)) {
                echo json_encode(['success' => true, 'message' => 'تم تحديث المخزون بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
            }
        }
    }

    public function get_product_stock($product_id)
    {
        $stocks = $this->Stock_model->get_stock_by_product($product_id);
        echo json_encode($stocks);
    }
}
