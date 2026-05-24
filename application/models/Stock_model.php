<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Stock_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_stock_list($product_id = null, $warehouse_id = null)
    {
        $this->db->select('
            products.id as product_id,
            products.code,
            products.name as product_name,
            warehouses.id as warehouse_id,
            warehouses.name as warehouse_name,
            product_warehouse.quantity,
            products.alert_quantity
        ');
        $this->db->from('product_warehouse');
        $this->db->join('products', 'products.id = product_warehouse.product_id');
        $this->db->join('warehouses', 'warehouses.id = product_warehouse.warehouse_id');
        $this->db->where('products.is_active', 1);
        $this->db->where('warehouses.is_active', 1);

        if ($product_id) {
            $this->db->where('product_warehouse.product_id', $product_id);
        }

        if ($warehouse_id) {
            $this->db->where('product_warehouse.warehouse_id', $warehouse_id);
        }

        $this->db->order_by('products.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function get_quantity($product_id, $warehouse_id)
    {
        $this->db->where('product_id', $product_id);
        $this->db->where('warehouse_id', $warehouse_id);
        $query = $this->db->get('product_warehouse');
        $row = $query->row();
        return $row ? $row->quantity : 0;
    }

    public function get_stock_by_product($product_id)
    {
        $this->db->select('warehouses.name as warehouse_name, product_warehouse.quantity');
        $this->db->from('product_warehouse');
        $this->db->join('warehouses', 'warehouses.id = product_warehouse.warehouse_id');
        $this->db->where('product_warehouse.product_id', $product_id);
        $this->db->order_by('warehouses.name', 'ASC');
        $query = $this->db->get();
        return $query->result();
    }

    public function update_stock($product_id, $warehouse_id, $quantity, $operation = 'add')
    {
        $current = $this->get_quantity($product_id, $warehouse_id);

        if ($operation == 'add') {
            $new_quantity = $current + $quantity;
        } elseif ($operation == 'subtract') {
            $new_quantity = $current - $quantity;
        } else {
            $new_quantity = $quantity; // set directly
        }

        if ($new_quantity < 0) {
            return false;
        }

        $this->db->where('product_id', $product_id);
        $this->db->where('warehouse_id', $warehouse_id);
        $exists = $this->db->get('product_warehouse')->num_rows() > 0;

        if ($exists) {
            $this->db->where('product_id', $product_id);
            $this->db->where('warehouse_id', $warehouse_id);
            return $this->db->update('product_warehouse', ['quantity' => $new_quantity]);
        } else {
            return $this->db->insert('product_warehouse', [
                'product_id' => $product_id,
                'warehouse_id' => $warehouse_id,
                'quantity' => $new_quantity
            ]);
        }
    }

    public function get_low_stock_products($warehouse_id = null)
    {
        $this->db->select('
            products.id,
            products.code,
            products.name,
            products.alert_quantity,
            product_warehouse.quantity,
            warehouses.name as warehouse_name,
            (products.alert_quantity - product_warehouse.quantity) as shortage
        ');
        $this->db->from('product_warehouse');
        $this->db->join('products', 'products.id = product_warehouse.product_id');
        $this->db->join('warehouses', 'warehouses.id = product_warehouse.warehouse_id');
        $this->db->where('product_warehouse.quantity <= products.alert_quantity');
        $this->db->where('products.is_active', 1);

        if ($warehouse_id) {
            $this->db->where('product_warehouse.warehouse_id', $warehouse_id);
        }

        $this->db->order_by('shortage', 'DESC');
        $query = $this->db->get();
        return $query->result();
    }

}
