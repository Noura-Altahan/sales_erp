<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Invoice_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_customers()
    {
        $this->db->where('is_active', 1);
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('customers');
        return $query->result();
    }

    public function search_products($search, $warehouse_id)
    {
        $this->db->select('
            products.id,
            products.code,
            products.name,
            products.price,
            product_warehouse.quantity as stock
        ');
        $this->db->from('products');
        $this->db->join('product_warehouse', 'product_warehouse.product_id = products.id');
        $this->db->where('product_warehouse.warehouse_id', $warehouse_id);
        $this->db->where('products.is_active', 1);
        $this->db->where('product_warehouse.quantity >', 0);

        $this->db->group_start();
        $this->db->like('products.code', $search);
        $this->db->or_like('products.name', $search);
        $this->db->group_end();

        $this->db->limit(10);
        $query = $this->db->get();
        return $query->result();
    }

    public function generate_invoice_number()
    {
        $this->db->select_max('id');
        $query = $this->db->get('invoices');
        $row = $query->row();
        $next_id = ($row->id ?? 0) + 1;
        return 'INV-' . date('Ymd') . '-' . str_pad($next_id, 4, '0', STR_PAD_LEFT);
    }

    public function save_invoice($customer_id, $warehouse_id, $invoice_no, $items, $discount_percent, $user_id)
    {
        $subtotal = 0;
        foreach ($items as $item) {
            $subtotal += $item['price'] * $item['quantity'];
        }

        $discount_amount = ($subtotal * $discount_percent) / 100;
        $total = $subtotal - $discount_amount;

        $invoice_data = [
            'invoice_no' => $invoice_no,
            'customer_id' => $customer_id,
            'warehouse_id' => $warehouse_id,
            'date' => date('Y-m-d H:i:s'),
            'subtotal' => $subtotal,
            'discount_percent' => $discount_percent,
            'discount_amount' => $discount_amount,
            'total' => $total,
            'created_by' => $user_id,
            'status' => 'completed'
        ];

        $this->db->insert('invoices', $invoice_data);
        $invoice_id = $this->db->insert_id();

        foreach ($items as $item) {
            $item_data = [
                'invoice_id' => $invoice_id,
                'product_id' => $item['product_id'],
                'quantity' => $item['quantity'],
                'price' => $item['price'],
                'total' => $item['price'] * $item['quantity']
            ];
            $this->db->insert('invoice_items', $item_data);
        }

        return $invoice_id;
    }

    public function get_all_invoices()
    {
        $this->db->select('
        invoices.*,
        customers.name as customer_name,
        warehouses.name as warehouse_name,
        users.username as created_by_name
    ');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->join('warehouses', 'warehouses.id = invoices.warehouse_id');
        $this->db->join('users', 'users.id = invoices.created_by', 'left');
        $this->db->order_by('invoices.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_invoices_by_warehouse($warehouse_id)
    {
        $this->db->select('
        invoices.*,
        customers.name as customer_name,
        warehouses.name as warehouse_name,
        users.username as created_by_name
    ');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->join('warehouses', 'warehouses.id = invoices.warehouse_id');
        $this->db->join('users', 'users.id = invoices.created_by', 'left');
        $this->db->where('invoices.warehouse_id', $warehouse_id);
        $this->db->order_by('invoices.id', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_invoice_by_id($id)
    {
        $this->db->select('
        invoices.*,
        customers.name as customer_name,
        customers.phone as customer_phone,
        customers.email as customer_email,
        customers.address as customer_address,
        warehouses.name as warehouse_name,
        users.username as created_by_name
    ');
        $this->db->from('invoices');
        $this->db->join('customers', 'customers.id = invoices.customer_id');
        $this->db->join('warehouses', 'warehouses.id = invoices.warehouse_id');
        $this->db->join('users', 'users.id = invoices.created_by', 'left');
        $this->db->where('invoices.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function get_invoice_items($invoice_id)
    {
        $this->db->select('
        invoice_items.*,
        products.code,
        products.name as product_name
    ');
        $this->db->from('invoice_items');
        $this->db->join('products', 'products.id = invoice_items.product_id');
        $this->db->where('invoice_items.invoice_id', $invoice_id);
        $query = $this->db->get();
        return $query->result();
    }

    public function delete_invoice($id)
    {
        $this->db->where('invoice_id', $id);
        $this->db->delete('invoice_items');

        $this->db->where('id', $id);
        return $this->db->delete('invoices');
    }
}
