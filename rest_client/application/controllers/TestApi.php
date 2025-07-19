<?php
defined('BASEPATH') OR exit('No direct script access allowed');

// Pastikan Anda sudah menginstall Guzzle via Composer di folder rest_client
// dan sudah mengkonfigurasi autoload di config.php
// $config['composer_autoload'] = TRUE; atau FCPATH . 'vendor/autoload.php';

class TestApi extends CI_Controller {

    public function index()
    {
        // Set header agar outputnya terlihat rapi sebagai teks biasa
        header('Content-Type: text/plain');

        try {
            // Inisialisasi Guzzle Client
            $client = new \GuzzleHttp\Client();

            // URL API di rest_server
            $url = 'http://localhost/y/rest_server/api/calon';

            echo "Mencoba menghubungi: " . $url . "\n\n";

            // Lakukan request GET ke API
            $response = $client->request('GET', $url, [
                'http_errors' => false // Penting untuk debugging, agar tidak melempar exception pada error 4xx/5xx
            ]);

            // Tampilkan hasil
            echo "=================[ HASIL RESPONSE ]=================\n";
            echo "HTTP Status Code: " . $response->getStatusCode() . "\n";
            echo "---------------------[ BODY ]-----------------------\n";
            echo $response->getBody()->getContents();
            echo "\n====================================================\n";

        } catch (\GuzzleHttp\Exception\RequestException $e) {
            // Ini akan menangkap error koneksi (misal: server down, URL salah)
            echo "=================[ KONEKSI GAGAL ]=================\n";
            echo "Guzzle Request Exception: " . $e->getMessage();
            echo "\n=====================================================\n";
        }
    }
}
