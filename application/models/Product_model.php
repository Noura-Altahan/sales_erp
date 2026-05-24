<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Product_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_categories()
    {
        return $this->db->get('categories')->result();
    }

    public function get_products_paginated($search = '', $category_id = '', $page = 1, $per_page = 4)
    {
        $offset = ($page - 1) * $per_page;

        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        if ($search) {
            $this->db->group_start();
            $this->db->like('products.code', $search);
            $this->db->or_like('products.name', $search);
            $this->db->group_end();
        }

        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }

        $this->db->limit($per_page, $offset);
        $this->db->order_by('products.id', 'DESC');

        $query = $this->db->get();
        return $query->result();
    }

    public function count_products($search = '', $category_id = '')
    {
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');

        if ($search) {
            $this->db->group_start();
            $this->db->like('products.code', $search);
            $this->db->or_like('products.name', $search);
            $this->db->group_end();
        }

        if ($category_id) {
            $this->db->where('products.category_id', $category_id);
        }

        return $this->db->count_all_results();
    }

    public function insert_product($data)
    {
        return $this->db->insert('products', $data);
    }

    public function get_product_by_id($id)
    {
        $this->db->select('products.*, categories.name as category_name');
        $this->db->from('products');
        $this->db->join('categories', 'categories.id = products.category_id', 'left');
        $this->db->where('products.id', $id);
        $query = $this->db->get();
        return $query->row();
    }

    public function update_product($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('products', $data);
    }

    public function delete_product($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('products');
    }
}
