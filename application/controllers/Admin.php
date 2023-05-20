<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Admin extends RestController {

		public function __construct(){
		parent::__construct();
		$this->load->helper('jwt');
		$this->load->model('M_Login');
		$this->load->model('M_History');
	}

	public function login_post(){
		$jwt = new JWT();
		$jwtsecretkey = 'HS256';

		$username = $this->input->post('username');
		$password = $this->input->post('password');

		$cek = $this->M_Login->proses_login_admin($username, $password);
		if ($cek) {
			$this->response([
				'status' => true,
				'message' => 'Login berhasil',
				'data' => $cek,
				'token' => $jwt->encode($cek, $jwtsecretkey)
			], restController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Login gagal'
			], restController::HTTP_NOT_FOUND);
		}

	}

	public function history_get() {
        // Dapatkan data transaksi dari model
        $transaksi = $this->M_History->get_transaksi();

        // Tampilkan data transaksi dalam format JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($transaksi));
    }

}
