<?php
defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . 'libraries/REST_Controller.php';
use Restserver\Libraries\REST_Controller;

class Auth extends REST_Controller {

    function __construct($config = 'rest') {
        parent::__construct($config);
        $this->load->model('Pemilih_model', 'pemilih');
        
        // Pastikan kolom created_at ada di tabel pemilih
        $this->pemilih->ensure_created_at_column();
    }

    /**
     * Endpoint untuk login pemilih menggunakan NIM dan Password.
     * Metode: POST
     * Parameter: nim, password
     */
    public function login_post()
    {
        $nim = $this->post('nim');
        $password = $this->post('password');

        // Panggil model untuk mencari pemilih berdasarkan NIM
        $pemilih = $this->pemilih->cek_login_by_nim($nim);

        // Jika pemilih dengan NIM tersebut ditemukan
        if ($pemilih) {
            $password_valid = false;
            
            // Cek berbagai format password
            if (password_verify($password, $pemilih['password'])) {
                // Password bcrypt
                $password_valid = true;
            } elseif (md5($password) === $pemilih['password']) {
                // Password MD5
                $password_valid = true;
            } elseif ($password === $pemilih['password']) {
                // Password plain text
                $password_valid = true;
            }
            
            if ($password_valid) {
                
                // Hapus field password dari data yang akan dikirim kembali untuk keamanan
                unset($pemilih['password']);

                // Kirim respons sukses beserta data pemilih
                $this->response([
                    'status' => TRUE,
                    'message' => 'Login berhasil.',
                    'data' => $pemilih
                ], REST_Controller::HTTP_OK);
            } else {
                // Jika password salah
                $this->response([
                    'status' => FALSE,
                    'message' => 'Password yang Anda masukkan salah.'
                ], REST_Controller::HTTP_BAD_REQUEST);
            }
        } else {
            // Jika NIM tidak ditemukan
            $this->response(['status' => FALSE, 'message' => 'NIM tidak ditemukan.'], REST_Controller::HTTP_NOT_FOUND);
        }
    }

    /**
     * Endpoint untuk registrasi pemilih baru.
     * Metode: POST
     * Parameter: nim, nama, password, confirm_password
     */
    public function register_post()
    {
        $nim = $this->post('nim');
        $nama = $this->post('nama');
        $password = $this->post('password');
        $confirm_password = $this->post('confirm_password');

        // Validasi input
        if (empty($nim) || empty($nama) || empty($password) || empty($confirm_password)) {
            $this->response([
                'status' => FALSE,
                'message' => 'Semua field harus diisi.'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Validasi NIM format (contoh: harus numerik dan panjang tertentu)
        if (!is_numeric($nim) || strlen($nim) < 8 || strlen($nim) > 15) {
            $this->response([
                'status' => FALSE,
                'message' => 'Format NIM tidak valid. NIM harus berupa angka dengan panjang 8-15 digit.'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Validasi nama (tidak boleh kosong dan minimal 2 karakter)
        if (strlen(trim($nama)) < 2) {
            $this->response([
                'status' => FALSE,
                'message' => 'Nama harus minimal 2 karakter.'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Validasi password
        if (strlen($password) < 6) {
            $this->response([
                'status' => FALSE,
                'message' => 'Password harus minimal 6 karakter.'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Validasi konfirmasi password
        if ($password !== $confirm_password) {
            $this->response([
                'status' => FALSE,
                'message' => 'Konfirmasi password tidak sesuai.'
            ], REST_Controller::HTTP_BAD_REQUEST);
            return;
        }

        // Cek apakah NIM sudah terdaftar
        $existing_pemilih = $this->pemilih->cek_login_by_nim($nim);
        if ($existing_pemilih) {
            $this->response([
                'status' => FALSE,
                'message' => 'NIM sudah terdaftar. Silakan gunakan NIM yang lain atau login jika sudah memiliki akun.'
            ], REST_Controller::HTTP_CONFLICT);
            return;
        }

        // Persiapkan data untuk disimpan
        $data_pemilih = [
            'nim' => $nim,
            'nama' => trim($nama),
            'password' => password_hash($password, PASSWORD_DEFAULT), // Enkripsi password dengan bcrypt
            'status_memilih' => 0, // Belum memilih
            'created_at' => date('Y-m-d H:i:s')
        ];

        // Simpan data pemilih baru
        $result = $this->pemilih->createPemilih($data_pemilih);

        if ($result > 0) {
            // Registrasi berhasil
            $this->response([
                'status' => TRUE,
                'message' => 'Registrasi berhasil! Silakan login dengan NIM dan password yang telah didaftarkan.',
                'data' => [
                    'nim' => $nim,
                    'nama' => $nama
                ]
            ], REST_Controller::HTTP_CREATED);
        } else {
            // Gagal menyimpan data
            $this->response([
                'status' => FALSE,
                'message' => 'Gagal menyimpan data. Silakan coba lagi.'
            ], REST_Controller::HTTP_INTERNAL_ERROR);
        }
    }
}
