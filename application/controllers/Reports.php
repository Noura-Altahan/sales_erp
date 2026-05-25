<?php
defined('BASEPATH') or exit('No direct script access allowed');

class Reports extends CI_Controller
{

    public function __construct()
    {
        parent::__construct();

        if (!$this->session->userdata('logged_in')) {
            redirect('auth/login');
        }

        $this->load->model('Stock_model');
        $this->load->model('Product_model');
        $this->load->helper('download');
    }

    public function low_stock()
    {
        $data['title'] = 'تقرير المخزون المنخفض';
        $data['active_menu'] = 'low_stock';

        if ($this->session->userdata('role') == 'admin') {
            $data['warehouses'] = $this->db->get('warehouses')->result();
            $warehouse_id = $this->input->get('warehouse_id');
        } else {
            $warehouse_id = $this->session->userdata('warehouse_id');
            $this->db->where('id', $warehouse_id);
            $data['warehouses'] = $this->db->get('warehouses')->result();
        }

        $data['low_stock_products'] = $this->Stock_model->get_low_stock_products($warehouse_id);

        $data['stats'] = $this->calculate_stats($data['low_stock_products']);

        $this->load->view('templates/header', $data);
        $this->load->view('reports/low_stock', $data);
        $this->load->view('templates/footer');
    }

    public function export_csv()
    {
        $warehouse_id = $this->input->get('warehouse_id');

        if ($this->session->userdata('role') != 'admin') {
            $warehouse_id = $this->session->userdata('warehouse_id');
        }

        $low_stock_products = $this->Stock_model->get_low_stock_products($warehouse_id);

        $csv_content = "\xEF\xBB\xBF";

        $headers = ['كود المنتج', 'اسم المنتج', 'المستودع', 'الكمية الحالية', 'كمية التنبيه', 'مقدار النقص', 'الحالة'];
        $csv_content .= implode(',', $headers) . "\n";

        foreach ($low_stock_products as $product) {
            $status = '';
            if ($product->quantity == 0) {
                $status = 'منعدم';
            } elseif ($product->quantity <= $product->alert_quantity / 2) {
                $status = 'حرج';
            } else {
                $status = 'منخفض';
            }

            $row = [
                "\"{$product->code}\"",
                "\"{$product->name}\"",
                "\"{$product->warehouse_name}\"",
                $product->quantity,
                $product->alert_quantity,
                $product->shortage,
                "\"{$status}\""
            ];

            $csv_content .= implode(',', $row) . "\n";
        }

        $filename = 'low_stock_report_' . date('Y-m-d_H-i-s') . '.csv';

        $this->output
            ->set_content_type('text/csv; charset=utf-8')
            ->set_header('Content-Disposition: attachment; filename="' . $filename . '"')
            ->set_header('Cache-Control: no-cache')
            ->set_output($csv_content);
    }

    private function calculate_stats($products)
    {
        $stats = [
            'total_products' => count($products),
            'total_shortage' => 0,
            'critical_count' => 0,
            'warning_count' => 0
        ];

        foreach ($products as $product) {
            $stats['total_shortage'] += $product->shortage;

            if ($product->quantity == 0) {
                $stats['critical_count']++;
            } elseif ($product->quantity <= $product->alert_quantity / 2) {
                $stats['warning_count']++;
            }
        }

        return $stats;
    }
}
