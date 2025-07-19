<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Calon_model extends CI_Model
{
    public function getCalon($id = null)
    {
        if ($id === null) {
            return $this->db->get('calon')->result_array();
        } else {
            return $this->db->get_where('calon', ['id_calon' => $id])->row_array();
        }
    }

    public function deleteCalon($id)
    {
        $this->db->delete('calon', ['id_calon' => $id]);
        return $this->db->affected_rows();
    }

    public function createCalon($data)
    {
        $this->db->insert('calon', $data);
        return $this->db->affected_rows();
    }

    public function updateCalon($data, $id)
    {
        $this->db->update('calon', $data, ['id_calon' => $id]);
        return $this->db->affected_rows();
    }

    public function get_all_calon()
    {
        return $this->db->get('calon')->result_array();
    }

    public function tambah_suara($id_calon)
    {
        $this->db->set('jumlah_suara', 'jumlah_suara+1', FALSE);
        $this->db->where('id_calon', $id_calon);
        $this->db->update('calon');
        return $this->db->affected_rows();
    }
}
