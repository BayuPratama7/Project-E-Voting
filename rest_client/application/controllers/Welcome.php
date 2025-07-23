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
		// Cek apakah sudah login admin
		if ($this->session->userdata('status') === 'login' && $this->session->userdata('is_admin') === TRUE) {
			redirect('admin/dashboard');
		}
		
		// Cek apakah sudah login pemilih
		if ($this->session->userdata('pemilih_status') === 'login') {
			redirect('dashboard_pemilih/dashboard');
		}
		
		// Tampilkan halaman pilihan role
		$this->load->view('portal_login');
	}
}
