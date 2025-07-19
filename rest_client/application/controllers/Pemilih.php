<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use GuzzleHttp\Client;
use GuzzleHttp\Exception\ClientException;
use GuzzleHttp\Exception\ConnectException;

class Pemilih extends CI_Controller {

    private $_client;

    public function __construct()
    {
        parent::__construct();
        // Cek session, jika tidak ada, tendang ke login
        if ($this->session->userdata('status') !== 'login') {
            redirect('auth/login');
        }

        // Muat helper yang diperlukan
        $this->load->helper(array('form', 'url'));

        // Inisialisasi Guzzle Client
        $this->_client = new Client([
            'base_uri' => 'http://localhost/y/rest_server/api/', // Sesuaikan dengan URL API Anda
            'timeout'  => 5.0
        ]);
    }

    /**
     * Menampilkan daftar semua pemilih (READ)
     */
    public function index()
    {
        try {
            $response = $this->_client->request('GET', 'pemilih');
            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data']) && is_array($result['data'])) {
                $data['pemilih'] = $result['data'];
            } else {
                $data['pemilih'] = [];
                $errorMessage = isset($result['message']) ? $result['message'] : 'Gagal mengambil data atau format tidak valid.';
                $this->session->set_flashdata('error', $errorMessage);
            }
            $data['title'] = "Kelola Data Pemilih";
            $this->load->view('pemilih/index', $data);

        } catch (ConnectException $e) {
            show_error("Tidak dapat terhubung ke API server. Pastikan rest_server berjalan dan URL benar.", 503, "Koneksi Gagal");
        } catch (ClientException $e) {
            $response = $e->getResponse();
            show_error("Gagal mengambil data dari API: " . $response->getBody()->getContents(), $response->getStatusCode(), "Error dari API Server");
        }
    }

    /**
     * Mendapatkan data pemilih untuk API (JSON response)
     */
    public function get_data()
    {
        try {
            $response = $this->_client->request('GET', 'pemilih');
            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data']) && is_array($result['data'])) {
                // Return data sebagai JSON untuk AJAX request
                header('Content-Type: application/json');
                echo json_encode($result);
            } else {
                header('Content-Type: application/json');
                echo json_encode(['status' => false, 'message' => 'Gagal mengambil data pemilih']);
            }

        } catch (ConnectException $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'Tidak dapat terhubung ke API server']);
        } catch (ClientException $e) {
            header('Content-Type: application/json');
            echo json_encode(['status' => false, 'message' => 'Gagal mengambil data dari API']);
        }
    }

    /**
     * Mengubah status memilih pemilih
     */
    public function ubah_status($id_pemilih = null)
    {
        if ($id_pemilih === null) {
            $this->session->set_flashdata('error', 'ID Pemilih tidak valid.');
            redirect('pemilih');
            return;
        }

        try {
            // Ambil data pemilih saat ini
            $response = $this->_client->request('GET', 'pemilih?id=' . $id_pemilih);
            $result = json_decode($response->getBody()->getContents(), true);

            if (!isset($result['data'][0])) {
                $this->session->set_flashdata('error', 'Data pemilih tidak ditemukan.');
                redirect('pemilih');
                return;
            }

            $pemilih = $result['data'][0];
            $status_baru = ($pemilih['status_memilih'] == '1') ? '0' : '1';

            // Update status memilih
            $updateResponse = $this->_client->request('PUT', 'pemilih', [
                'form_params' => [
                    'id' => $id_pemilih,
                    'status_memilih' => $status_baru
                ]
            ]);

            $updateResult = json_decode($updateResponse->getBody()->getContents(), true);

            if (isset($updateResult['status']) && $updateResult['status'] === true) {
                $status_text = ($status_baru == '1') ? 'Sudah Memilih' : 'Belum Memilih';
                $this->session->set_flashdata('success', 'Status pemilih ' . $pemilih['nama'] . ' berhasil diubah menjadi: ' . $status_text);
            } else {
                $error_msg = isset($updateResult['message']) ? $updateResult['message'] : 'Gagal mengubah status pemilih.';
                $this->session->set_flashdata('error', $error_msg);
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            $error_msg = isset($error['message']) ? $error['message'] : 'Gagal mengubah status pemilih.';
            $this->session->set_flashdata('error', $error_msg);
        } catch (ConnectException $e) {
            $this->session->set_flashdata('error', 'Tidak dapat terhubung ke API server.');
        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan sistem.');
        }

        redirect('pemilih');
    }

    /**
     * Reset semua status memilih menjadi "Belum Memilih"
     */
    public function reset_semua_status()
    {
        try {
            // Ambil semua data pemilih
            $response = $this->_client->request('GET', 'pemilih');
            $result = json_decode($response->getBody()->getContents(), true);

            if (!isset($result['data']) || !is_array($result['data'])) {
                $this->session->set_flashdata('error', 'Gagal mengambil data pemilih.');
                redirect('pemilih');
                return;
            }

            $success_count = 0;
            $error_count = 0;

            foreach ($result['data'] as $pemilih) {
                if ($pemilih['status_memilih'] == '1') {
                    try {
                        $updateResponse = $this->_client->request('PUT', 'pemilih', [
                            'form_params' => [
                                'id' => $pemilih['id_pemilih'],
                                'status_memilih' => '0'
                            ]
                        ]);

                        $updateResult = json_decode($updateResponse->getBody()->getContents(), true);
                        
                        if (isset($updateResult['status']) && $updateResult['status'] === true) {
                            $success_count++;
                        } else {
                            $error_count++;
                        }
                    } catch (Exception $e) {
                        $error_count++;
                    }
                }
            }

            if ($success_count > 0) {
                $this->session->set_flashdata('success', 'Berhasil mereset ' . $success_count . ' status pemilih menjadi "Belum Memilih".');
            }
            if ($error_count > 0) {
                $this->session->set_flashdata('error', 'Gagal mereset ' . $error_count . ' status pemilih.');
            }
            if ($success_count == 0 && $error_count == 0) {
                $this->session->set_flashdata('info', 'Tidak ada pemilih yang perlu direset.');
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mereset status.');
        }

        redirect('pemilih');
    }

    /**
     * Reset suara semua calon menjadi 0
     */
    public function reset_suara_calon()
    {
        // Untuk sementara, fitur ini akan memberikan pesan bahwa fitur sedang dalam pengembangan
        $this->session->set_flashdata('info', 'Fitur reset suara calon sedang dalam pengembangan. Saat ini Anda dapat menggunakan phpMyAdmin untuk mereset kolom jumlah_suara di tabel calon menjadi 0.');
        redirect('pemilih');
    }

    /**
     * Tampilkan form tambah pemilih baru (CREATE)
     */
    public function tambah()
    {
        $data['title'] = "Tambah Data Pemilih";
        $this->load->view('pemilih/tambah', $data);
    }

    /**
     * Proses tambah pemilih baru
     */
    public function simpan()
    {
        $this->load->library('form_validation');
        
        // Set validation rules
        $this->form_validation->set_rules('nim', 'NIM', 'required|min_length[8]|max_length[15]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|min_length[3]|max_length[100]');
        $this->form_validation->set_rules('password', 'Password', 'required|min_length[6]');

        if ($this->form_validation->run() == FALSE) {
            $data['title'] = "Tambah Data Pemilih";
            $this->load->view('pemilih/tambah', $data);
        } else {
            try {
                $data_pemilih = [
                    'nim' => $this->input->post('nim'),
                    'nama' => $this->input->post('nama'),
                    'password' => password_hash($this->input->post('password'), PASSWORD_DEFAULT),
                    'status_memilih' => 0
                ];

                $response = $this->_client->request('POST', 'pemilih', [
                    'form_params' => $data_pemilih
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result['status']) && $result['status'] === true) {
                    $this->session->set_flashdata('success', 'Data pemilih berhasil ditambahkan.');
                    redirect('pemilih');
                } else {
                    $error_msg = isset($result['message']) ? $result['message'] : 'Gagal menambah data pemilih.';
                    $this->session->set_flashdata('error', $error_msg);
                    redirect('pemilih/tambah');
                }

            } catch (ClientException $e) {
                $response = $e->getResponse();
                $error = json_decode($response->getBody()->getContents(), true);
                $error_msg = isset($error['message']) ? $error['message'] : 'Gagal menambah data pemilih.';
                $this->session->set_flashdata('error', $error_msg);
                redirect('pemilih/tambah');
            } catch (ConnectException $e) {
                $this->session->set_flashdata('error', 'Tidak dapat terhubung ke API server.');
                redirect('pemilih/tambah');
            }
        }
    }

    /**
     * Tampilkan form edit pemilih (UPDATE)
     */
    public function edit($id_pemilih = null)
    {
        if ($id_pemilih === null) {
            $this->session->set_flashdata('error', 'ID Pemilih tidak valid.');
            redirect('pemilih');
            return;
        }

        try {
            $response = $this->_client->request('GET', 'pemilih?id=' . $id_pemilih);
            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['data'][0])) {
                $data['pemilih'] = $result['data'][0];
                $data['title'] = "Edit Data Pemilih";
                $this->load->view('pemilih/edit', $data);
            } else {
                $this->session->set_flashdata('error', 'Data pemilih tidak ditemukan.');
                redirect('pemilih');
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Gagal mengambil data pemilih.');
            redirect('pemilih');
        }
    }

    /**
     * Proses update data pemilih
     */
    public function update()
    {
        $this->load->library('form_validation');
        
        // Set validation rules
        $this->form_validation->set_rules('id_pemilih', 'ID Pemilih', 'required');
        $this->form_validation->set_rules('nim', 'NIM', 'required|min_length[8]|max_length[15]');
        $this->form_validation->set_rules('nama', 'Nama', 'required|min_length[3]|max_length[100]');

        if ($this->form_validation->run() == FALSE) {
            $this->edit($this->input->post('id_pemilih'));
        } else {
            try {
                $data_pemilih = [
                    'id' => $this->input->post('id_pemilih'),
                    'nim' => $this->input->post('nim'),
                    'nama' => $this->input->post('nama')
                ];

                // Add password if provided
                if (!empty($this->input->post('password'))) {
                    $data_pemilih['password'] = password_hash($this->input->post('password'), PASSWORD_DEFAULT);
                }

                $response = $this->_client->request('PUT', 'pemilih', [
                    'form_params' => $data_pemilih
                ]);

                $result = json_decode($response->getBody()->getContents(), true);

                if (isset($result['status']) && $result['status'] === true) {
                    $this->session->set_flashdata('success', 'Data pemilih berhasil diupdate.');
                    redirect('pemilih');
                } else {
                    $error_msg = isset($result['message']) ? $result['message'] : 'Gagal mengupdate data pemilih.';
                    $this->session->set_flashdata('error', $error_msg);
                    redirect('pemilih/edit/' . $this->input->post('id_pemilih'));
                }

            } catch (ClientException $e) {
                $response = $e->getResponse();
                $error = json_decode($response->getBody()->getContents(), true);
                $error_msg = isset($error['message']) ? $error['message'] : 'Gagal mengupdate data pemilih.';
                $this->session->set_flashdata('error', $error_msg);
                redirect('pemilih/edit/' . $this->input->post('id_pemilih'));
            } catch (ConnectException $e) {
                $this->session->set_flashdata('error', 'Tidak dapat terhubung ke API server.');
                redirect('pemilih/edit/' . $this->input->post('id_pemilih'));
            }
        }
    }

    /**
     * Hapus data pemilih (DELETE)
     */
    public function hapus($id_pemilih = null)
    {
        if ($id_pemilih === null) {
            $this->session->set_flashdata('error', 'ID Pemilih tidak valid.');
            redirect('pemilih');
            return;
        }

        try {
            $response = $this->_client->request('DELETE', 'pemilih', [
                'form_params' => [
                    'id' => $id_pemilih
                ]
            ]);

            $result = json_decode($response->getBody()->getContents(), true);

            if (isset($result['status']) && $result['status'] === true) {
                $this->session->set_flashdata('success', 'Data pemilih berhasil dihapus.');
            } else {
                $error_msg = isset($result['message']) ? $result['message'] : 'Gagal menghapus data pemilih.';
                $this->session->set_flashdata('error', $error_msg);
            }

        } catch (ClientException $e) {
            $response = $e->getResponse();
            $error = json_decode($response->getBody()->getContents(), true);
            $error_msg = isset($error['message']) ? $error['message'] : 'Gagal menghapus data pemilih.';
            $this->session->set_flashdata('error', $error_msg);
        } catch (ConnectException $e) {
            $this->session->set_flashdata('error', 'Tidak dapat terhubung ke API server.');
        }

        redirect('pemilih');
    }

    /**
     * Toggle status memilih pemilih
     */
    public function toggle_status($id_pemilih = null)
    {
        return $this->ubah_status($id_pemilih);
    }

    /**
     * Toggle semua status pemilih
     */
    public function toggle_all_status()
    {
        try {
            // Ambil semua data pemilih
            $response = $this->_client->request('GET', 'pemilih');
            $result = json_decode($response->getBody()->getContents(), true);

            if (!isset($result['data']) || !is_array($result['data'])) {
                $this->session->set_flashdata('error', 'Gagal mengambil data pemilih.');
                redirect('pemilih');
                return;
            }

            $success_count = 0;
            $error_count = 0;

            foreach ($result['data'] as $pemilih) {
                try {
                    $status_baru = ($pemilih['status_memilih'] == '1') ? '0' : '1';
                    
                    $updateResponse = $this->_client->request('PUT', 'pemilih', [
                        'form_params' => [
                            'id' => $pemilih['id_pemilih'],
                            'status_memilih' => $status_baru
                        ]
                    ]);

                    $updateResult = json_decode($updateResponse->getBody()->getContents(), true);
                    
                    if (isset($updateResult['status']) && $updateResult['status'] === true) {
                        $success_count++;
                    } else {
                        $error_count++;
                    }
                } catch (Exception $e) {
                    $error_count++;
                }
            }

            if ($success_count > 0) {
                $this->session->set_flashdata('success', 'Berhasil mengubah status ' . $success_count . ' pemilih.');
            }
            if ($error_count > 0) {
                $this->session->set_flashdata('error', 'Gagal mengubah status ' . $error_count . ' pemilih.');
            }

        } catch (Exception $e) {
            $this->session->set_flashdata('error', 'Terjadi kesalahan saat mengubah status.');
        }

        redirect('pemilih');
    }

    /**
     * Reset status voting semua pemilih
     */
    public function reset_all_status()
    {
        return $this->reset_semua_status();
    }

    /**
     * Reset semua votes
     */
    public function reset_votes()
    {
        return $this->reset_suara_calon();
    }
}
