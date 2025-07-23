<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calon_model extends CI_Model {

    public function __construct()
    {
        parent::__construct();
        $this->load->database();
    }

    public function get_all_calon()
    {
        $this->db->select('id_calon, nama_calon, foto, visi, misi, jumlah_suara');
        $this->db->from('calon');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_calon_by_id($id)
    {
        $this->db->select('*');
        $this->db->from('calon');
        $this->db->where('id_calon', $id);
        $query = $this->db->get();
        return $query->row_array();
    }

    public function insert_calon($data)
    {
        return $this->db->insert('calon', $data);
    }

    public function update_calon($id, $data)
    {
        $this->db->where('id_calon', $id);
        return $this->db->update('calon', $data);
    }

    public function delete_calon($id)
    {
        $this->db->where('id_calon', $id);
        return $this->db->delete('calon');
    }

    public function get_hasil_pemilihan()
    {
        $this->db->select('id_calon as id, nama_calon as nama, jumlah_suara');
        $this->db->from('calon');
        $this->db->order_by('jumlah_suara', 'DESC');
        $query = $this->db->get();
        return $query->result_array();
    }

    public function get_total_suara()
    {
        $this->db->select_sum('jumlah_suara');
        $query = $this->db->get('calon');
        $result = $query->row_array();
        return $result['jumlah_suara'] ? $result['jumlah_suara'] : 0;
    }

    public function reset_suara()
    {
        $data = array('jumlah_suara' => 0);
        return $this->db->update('calon', $data);
    }

    public function tambah_suara($id_calon)
    {
        $this->db->set('jumlah_suara', 'jumlah_suara + 1', FALSE);
        $this->db->where('id_calon', $id_calon);
        return $this->db->update('calon');
    }
}
