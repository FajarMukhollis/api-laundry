<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Admin extends RestController
{

	public function __construct()
	{
		parent::__construct();
		define('jwtsecretkey', 'R4ha5iA');
		$this->load->helper('jwt');
		$this->load->model('M_Login');
		$this->load->model('M_History');
		$this->load->library('form_validation');
	}

	public function login_post()
	{
		$jwt = new JWT();

		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);

		$username = $data['username'];
		$password = $data['password'];

		if (empty($username) || empty($password)) {
			$this->response([
				'status' => false,
				'message' => 'email and password are required'
			], RestController::HTTP_BAD_REQUEST);
		}

		$admin = $this->M_Login->proses_login_admin($username, md5($password));
		if ($admin) {
			$this->response([
				'status' => true,
				'message' => 'Login Berhasil',
				'token' => $jwt->encode($admin, jwtsecretkey),
				'data' => $admin

			], restController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Login Gagal'
			], restController::HTTP_NOT_FOUND);
		}
	}

	public function history_get()
	{
		// Dapatkan data transaksi dari model
		$transaksi = $this->M_History->get_transaksi();

		// Tampilkan data transaksi dalam format JSON
		$this->output->set_content_type('application/json')->set_output(json_encode($transaksi));
	}
}
