<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

	public function __construct() {
        parent::__construct();
        $this->load->helper(array('url', 'html'));
        $this->load->library('session');
    }

	public function index()
	{
		// Cek apakah ada session aktif, jika ada redirect ke dashboard masing-masing
		if ($this->session->userdata('status') === 'login') {
			redirect('dashboard/dashboard');
		} elseif ($this->session->userdata('pemilih_status') === 'login') {
			redirect('dashboard_pemilih');
		}
		
		// Tampilkan halaman pilihan login
		$this->load->view('welcome_message');
	}
}
