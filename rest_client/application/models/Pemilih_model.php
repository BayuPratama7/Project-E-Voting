<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Pemilih_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_pemilih()
    {
        $this->db->select('id_pemilih as id, nim, nama, status_memilih as status_vote, created_at as waktu_vote');
        $this->db->from('pemilih');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_pemilih_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('pemilih');
        $this->db->where('id_pemilih', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function get_total_pemilih()
    {
        return $this->db->count_all('pemilih');
    }

    public function insert_pemilih($data)
    {
        return $this->db->insert('pemilih', $data);
    }

    public function update_pemilih($id, $data)
    {
        $this->db->where('id_pemilih', $id);
        return $this->db->update('pemilih', $data);
    }

    public function delete_pemilih($id)
    {
        $this->db->where('id_pemilih', $id);
        return $this->db->delete('pemilih');
    }

    public function get_pemilih_by_nim($nim)
    {
        $this->db->where('nim', $nim);
        $query = $this->db->get('pemilih');
        return $query->row_array();
    }

    public function count_sudah_memilih()
    {
        $this->db->where('status_memilih', 1);
        return $this->db->count_all_results('pemilih');
    }

    public function count_belum_memilih()
    {
        $this->db->where('status_memilih', 0);
        return $this->db->count_all_results('pemilih');
    }
}
