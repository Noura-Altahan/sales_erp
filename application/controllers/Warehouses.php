<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouses extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $this->load->model('Warehouse_model');

        if ($this->session->userdata('role') != 'admin') {
            show_error('ليس لديك صلاحية للوصول إلى هذه الصفحة', 403);
        }
    }

    public function index()
    {
        $data['title'] = 'إدارة المستودعات';
        $data['active_menu'] = 'warehouses';
        $data['warehouses'] = $this->Warehouse_model->get_all_warehouses();

        $this->load->view('templates/header', $data);
        $this->load->view('warehouses/index', $data);
        $this->load->view('templates/footer');
    }

    public function add()
    {
        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'location' => $this->input->post('location'),
                'is_active' => 1
            );

            if ($this->Warehouse_model->insert_warehouse($data)) {
                echo json_encode(['success' => true, 'message' => 'تم إضافة المستودع بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
            }
        }
    }

    public function get_warehouse($id)
    {
        $warehouse = $this->Warehouse_model->get_warehouse_by_id($id);
        echo json_encode($warehouse);
    }

    public function edit($id)
    {
        if ($this->input->post()) {
            $data = array(
                'name' => $this->input->post('name'),
                'location' => $this->input->post('location')
            );

            if ($this->Warehouse_model->update_warehouse($id, $data)) {
                echo json_encode(['success' => true, 'message' => 'تم تحديث المستودع بنجاح']);
            } else {
                echo json_encode(['success' => false, 'message' => 'حدث خطأ']);
            }
        }
    }

    public function delete($id)
    {
        if ($this->Warehouse_model->delete_warehouse($id)) {
            echo json_encode(['success' => true, 'message' => 'تم حذف المستودع بنجاح']);
        } else {
            echo json_encode(['success' => false, 'message' => 'لا يمكن حذف المستودع لأنه مرتبط بمنتجات']);
        }
    }

    public function toggle_status($id)
    {
        $warehouse = $this->Warehouse_model->get_warehouse_by_id($id);
        $new_status = $warehouse->is_active == 1 ? 0 : 1;

        if ($this->Warehouse_model->update_warehouse($id, ['is_active' => $new_status])) {
            echo json_encode(['success' => true]);
        } else {
            echo json_encode(['success' => false]);
        }
    }
}
