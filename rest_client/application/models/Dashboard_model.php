<?php
class Dashboard_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_statistics()
    {
        // Contoh query untuk mengambil data statistik
        $query = $this->db->get('statistics'); // Ganti 'statistics' dengan nama tabel Anda
        return $query->result_array();
    }
}
