<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Admin_statistics extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        
        // Pastikan admin sudah login
        if (!$this->session->userdata('admin_logged_in')) {
            redirect('dashboard/login');
        }
    }

    /**
     * Halaman dashboard statistik untuk admin
     */
    public function index()
    {
        $data['title'] = 'Kelola Statistik Voting';
        $this->load->view('admin/statistics_dashboard', $data);
    }

    /**
     * API untuk mendapatkan data statistik secara real-time
     */
    public function get_stats()
    {
        header('Content-Type: application/json');
        
        try {
            // Gunakan Guzzle untuk mengambil data dari API
            $this->load->library('guzzle');
            $client = new \GuzzleHttp\Client();
            
            $response = $client->request('GET', base_url('rest_server/api/statistics/sinkronisasi'));
            $body = $response->getBody();
            $data = json_decode($body, true);
            
            echo json_encode($data);
            
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }

    /**
     * Reset statistik
     */
    public function reset_stats()
    {
        header('Content-Type: application/json');
        
        try {
            $this->load->library('guzzle');
            $client = new \GuzzleHttp\Client();
            
            $response = $client->request('POST', base_url('rest_server/api/statistics/reset'));
            $body = $response->getBody();
            $data = json_decode($body, true);
            
            echo json_encode($data);
            
        } catch (Exception $e) {
            echo json_encode([
                'status' => false,
                'message' => 'Error: ' . $e->getMessage()
            ]);
        }
    }
}
