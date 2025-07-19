<?php

class Pemilih_model extends CI_Model 
{
    public function getPemilih($id = null)
    {
        if ($id === null) {
            return $this->db->get('pemilih')->result_array();
        } else {
            return $this->db->get_where('pemilih', ['id_pemilih' => $id])->result_array();
        }
    }

    public function deletePemilih($id)
    {
        $this->db->delete('pemilih', ['id_pemilih' => $id]);
        return $this->db->affected_rows();
    }

    public function createPemilih($data)
    {
        $this->db->insert('pemilih', $data);
        return $this->db->affected_rows();
    }

    public function updatePemilih($data, $id)
    {
        $this->db->update('pemilih', $data, ['id_pemilih' => $id]);
        return $this->db->affected_rows();
    }

    /**
     * Cek login pemilih berdasarkan NIM
     * @param string $nim
     * @return array|null
     */
    public function cek_login_by_nim($nim) {
        return $this->db->get_where('pemilih', ['nim' => $nim])->row_array();
    }

    /**
     * Update status memilih pemilih
     * @param int $id_pemilih
     * @return int
     */
    public function update_status_memilih($id_pemilih) {
        $this->db->set('status_memilih', '1');
        $this->db->where('id_pemilih', $id_pemilih);
        $this->db->update('pemilih');
        return $this->db->affected_rows();
    }

    /**
     * Cek apakah kolom created_at ada di tabel pemilih
     * Jika tidak ada, tambahkan kolom tersebut
     */
    public function ensure_created_at_column() {
        // Cek apakah kolom created_at sudah ada
        $query = $this->db->query("SHOW COLUMNS FROM pemilih LIKE 'created_at'");
        if ($query->num_rows() == 0) {
            // Tambahkan kolom created_at jika belum ada
            $this->db->query("ALTER TABLE pemilih ADD COLUMN created_at DATETIME NULL DEFAULT NULL");
        }
    }
}
