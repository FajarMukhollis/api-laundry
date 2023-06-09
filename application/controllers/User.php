<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class User extends RestController
{

	public function __construct()
	{
		parent::__construct();
		define('jwtsecretkey', 'R4ha5iA');
		$this->load->model('M_Login');
		$this->load->model('M_Register');
		$this->load->model('M_History');
		$this->load->helper('jwt');
		$this->load->library('form_validation');
	}

	public function login_post()
	{
		$jwt = new JWT();
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);

		// Ambil data dari POST request
		$email = $data['email'];
		$password = $data['password'];

		// Validasi input
		if (empty($email) || empty($password)) {
			$this->response([
				'status' => false,
				'message' => 'email and password are required'
			], RestController::HTTP_BAD_REQUEST);
		}

		$cek = $this->M_Login->proses_login_user($email, $password);
		if ($cek) {
			$this->response([
				'status' => true,
				'message' => 'Login berhasil',
				'token' => $jwt->encode($cek, jwtsecretkey),
				'data' => $cek
			], restController::HTTP_OK);

			$this->session->set_userdata('id_pelanggan', $id_pelanggan);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Login gagal'
			], restController::HTTP_NOT_FOUND);
		}
	}


	public function register_post()
	{
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$nama_pelanggan = $data['nama_pelanggan'];
		$notelp = $data['no_telp'];
		$alamat = $data['alamat'];
		$email = $data['email'];
		$password = $data['password'];

		if ($this->M_Register->cek_email($this->post('email'))->num_rows() == 1) {
			$this->response([
				'status' => FALSE,
				'message' => 'Email sudah terdaftar, gunakan Email lain',
			], restController::HTTP_BAD_REQUEST);
		} else {

			$encrypted_password = md5($password);

			$register = [
				'nama_pelanggan' => $nama_pelanggan,
				'no_telp' => $notelp,
				'alamat' => $alamat,
				'email' => $email,
				'password' => $encrypted_password //bisa encrypt disini
			];
			$this->db->insert('pelanggan', $register);

			$this->response([
				'status' => TRUE,
				'message' => 'Registrasi berhasil.',
				'data' => $register
			], RestController::HTTP_OK);
		}
	}

	public function transaksi_get()
	{
		// Verifikasi otentikasi pelanggan di sini
		// Misalnya, Anda dapat menggunakan library JWT untuk verifikasi token
		$authorizationHeader = $this->input->get_request_header('Authorization');
		try {
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey)[0];
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Memanggil model untuk mendapatkan data transaksi
			$transaksi = $this->M_History->getTransaksiByPelanggan($idPelanggan);
			$this->response([
				'status' => TRUE,
				'transaksi' => $transaksi
			], RestController::HTTP_OK);
		} catch (Exception $e) {
			$this->response([
				'status' => 'gagal',
				'message' => 'Unauthorized'
			], RestController::HTTP_UNAUTHORIZED);
		}
	}

	public function transaksi_post()
	{
		$authorizationHeader = $this->input->get_request_header('Authorization');
		try {
			$this->load->library('form_validation');
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey)[0];
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Validasi Input
			$rules = $this->M_History->rules();
			$this->form_validation->set_rules($rules);


			if ($this->form_validation->run() == FALSE) {
				$errors = strip_tags(validation_errors());
				$errors = str_replace('\n', '', $errors);
				$errorArray = explode('.', $errors);
				$errorArray = array_map('trim', $errorArray);
				$errorArray = array_filter($errorArray);

				$this->response([
					'status' => false,
					'errors' => $errorArray,
				], RestController::HTTP_BAD_REQUEST);
			} else {
				$data = $this->input->post();
				// Insert
				$this->M_History->tambah_transaksi($idPelanggan, $data);
				$this->response([
					'status' => TRUE,
					'data' => $data
				], RestController::HTTP_CREATED);
			}
		} catch (Exception $e) {
			$this->response([
				'status' => 'gagal',
				'message' => 'Unauthorized'
			], RestController::HTTP_UNAUTHORIZED);
		}
	}
	public function transaksi_put()
	{
		$authorizationHeader = $this->input->get_request_header('Authorization');
		try {
			$this->load->library('form_validation');
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey)[0];
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Validasi Input
			$rules = $this->M_History->rules();
			$this->form_validation->set_data($this->put());
			$this->form_validation->set_rules($rules);


			if ($this->form_validation->run($this->input->method()) == FALSE) {
				$errors = strip_tags(validation_errors());
				$errors = str_replace('\n', '', $errors);
				$errorArray = explode('.', $errors);
				$errorArray = array_map('trim', $errorArray);
				$errorArray = array_filter($errorArray);


				$this->response([
					'status' => false,
					'errors' => $errorArray,
				], RestController::HTTP_BAD_REQUEST);
			} else {
				$data = $this->put();
				// Insert
				$this->M_History->update_transaksi($idPelanggan, $data);
				$this->response([
					'status' => TRUE,
					'data' => $data
				], RestController::HTTP_OK);
			}
		} catch (Exception $e) {
			$this->response([
				'status' => 'gagal',
				'message' => 'Unauthorized'
			], RestController::HTTP_UNAUTHORIZED);
		}
	}

	// public function history_get()
	// {
	// 	$id = $this->M_History->get_id_pelanggan($this->get('id_pelanggan'));

	// 	$data = $this->M_History->get_history($id[0]->id_pelanggan);
	// 	if ($data) {
	// 		$this->response([
	// 			'status' => false,
	// 			'message' => 'Data tidak ditemukan'
	// 		], RestController::HTTP_NOT_FOUND);
	// 	} else {
	// 		$this->response([
	// 			'status' => true,
	// 			'message' => 'Data ditemukan',
	// 			'data' => $data
	// 		], RestController::HTTP_OK);
	// 	}
	// }
}
