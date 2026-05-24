<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Warehouse_model extends CI_Model
{

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_warehouses()
    {
        $this->db->order_by('name', 'ASC');
        $query = $this->db->get('warehouses');
        return $query->result();
    }

    public function get_warehouse_by_id($id)
    {
        $this->db->where('id', $id);
        $query = $this->db->get('warehouses');
        return $query->row();
    }

    public function insert_warehouse($data)
    {
        return $this->db->insert('warehouses', $data);
    }

    public function update_warehouse($id, $data)
    {
        $this->db->where('id', $id);
        return $this->db->update('warehouses', $data);
    }

    public function delete_warehouse($id)
    {
        $this->db->where('id', $id);
        return $this->db->delete('warehouses');
    }
}
