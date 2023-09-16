<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class User extends RestController
{
	public function __construct()
	{
		parent::__construct();
		define('jwtsecretkey', 'R4ha5iA');
		$this->load->model('M_Users/M_Login');
		$this->load->model('M_Users/M_Register');
		$this->load->model('M_Users/M_History');
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
			$this->response(
				[
					'status' => false,
					'message' => 'email and password are required',
				],
				RestController::HTTP_BAD_REQUEST
			);
		}

		$cek = $this->M_Login->proses_login_user($email, md5($password));
		if ($cek) {
			$this->response(
				[
					'status' => true,
					'message' => 'Login berhasil',
					'token' => $jwt->encode($cek, jwtsecretkey),
					'data' => $cek,
				],
				restController::HTTP_OK
			);

			$this->session->set_userdata('id_pelanggan', $id_pelanggan);
		} else {
			$this->response(
				[
					'status' => false,
					'message' => 'Login gagal',
				],
				restController::HTTP_NOT_FOUND
			);
		}
	}

	public function register_post()
	{
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$nama_pelanggan = $data['nama_pelanggan'];
		$notelp = $data['no_telp'];
		$email = $data['email'];
		$password = $data['password'];

		if (
			$this->M_Register->cek_email($this->post('email'))->num_rows() == 1
		) {
			$this->response(
				[
					'status' => false,
					'message' => 'Email sudah terdaftar, gunakan Email lain',
				],
				restController::HTTP_BAD_REQUEST
			);
		} else {
			$encrypted_password = md5($password);

			$register = [
				'nama_pelanggan' => $nama_pelanggan,
				'no_telp' => $notelp,
				'email' => $email,
				'password' => $encrypted_password, //bisa encrypt disini
			];
			$this->db->insert('pelanggan', $register);

			$this->response(
				[
					'status' => true,
					'message' => 'Registrasi berhasil.',
					'data' => $register,
				],
				RestController::HTTP_OK
			);
		}
	}

	public function transaksi_get()
	{
		// Verifikasi otentikasi pelanggan di sini
		// Misalnya, Anda dapat menggunakan library JWT untuk verifikasi token
		$authorizationHeader = $this->input->get_request_header(
			'Authorization'
		);
		try {
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey);
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Memanggil model untuk mendapatkan data transaksi
			$transaksi = $this->M_History->getTransaksiByPelanggan(
				$idPelanggan
			);
			if ($transaksi) {
				$this->response(
					[
						'status' => true,
						'message' => 'Data ditemukan',
						'data' => $transaksi,
					],
					RestController::HTTP_OK
				);
			} else {
				$this->response(
					[
						'status' => false,
						'message' => 'Data tidak ditemukan',
					],
					RestController::HTTP_NOT_FOUND
				);
			}
		} catch (Exception $e) {
			$this->response(
				[
					'status' => 'gagal',
					'message' => 'Unauthorized',
				],
				RestController::HTTP_UNAUTHORIZED
			);
		}
	}

	public function detail_transaksi_get($id_transaksi)
	{
		// Verifikasi otentikasi pelanggan di sini
		// Misalnya, Anda dapat menggunakan library JWT untuk verifikasi token
		$authorizationHeader = $this->input->get_request_header(
			'Authorization'
		);
		try {
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey);
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Memanggil model untuk mendapatkan data transaksi
			$detailtransaksi = $this->M_History->detail_transaction(
				$idPelanggan,
				$id_transaksi
			);
			if ($detailtransaksi) {
				$this->response(
					[
						'status' => true,
						'message' => 'Data Ditemukan',
						'data' => $detailtransaksi,
					],
					RestController::HTTP_OK
				);
			} else {
				$this->response(
					[
						'status' => false,
						'message' => 'Data Tidak Ditemukan',
					],
					RestController::HTTP_NOT_FOUND
				);
			}
		} catch (Exception $e) {
			$this->response(
				[
					'status' => 'gagal',
					'message' => 'Unauthorized',
				],
				RestController::HTTP_UNAUTHORIZED
			);
		}
	}

	public function transaksi_post()
	{
		$authorizationHeader = $this->input->get_request_header(
			'Authorization'
		);
		try {
			$this->load->library('form_validation');
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey);
			// Mengakses nilai "id_pelanggan"
			$idPelanggan = $user->id_pelanggan;
			// Validasi Input
			$rules = $this->M_History->rules();
			$this->form_validation->set_rules($rules);

			if ($this->form_validation->run() == false) {
				$errors = strip_tags(validation_errors());
				$errors = str_replace('\n', '', $errors);
				$errorArray = explode('.', $errors);
				$errorArray = array_map('trim', $errorArray);
				$errorArray = array_filter($errorArray);

				$this->response(
					[
						'status' => false,
						'errors' => $errorArray,
					],
					RestController::HTTP_BAD_REQUEST
				);
			} else {
				$data = $this->input->post();
				// Insert
				$this->M_History->tambah_transaksi($idPelanggan, $data);
				$this->response(
					[
						'status' => true,
						'message' => 'Transaksi berhasil dilakukan',
						'data' => $data,
					],
					RestController::HTTP_CREATED
				);
			}
		} catch (Exception $e) {
			$this->response(
				[
					'status' => false,
					'message' => 'Unauthorized',
				],
				RestController::HTTP_UNAUTHORIZED
			);
		}
	}

	public function complaint_post()
	{
		$authorizationHeader = $this->input->get_request_header(
			'Authorization'
		);
		$id_transaksi = $this->post('id_transaksi');
		$komplen = $this->post('komplen');

		if (empty($id_transaksi)) {
			$this->response(
				[
					'status' => false,
					'message' => 'ID transaksi tidak valid',
				],
				RestController::HTTP_BAD_REQUEST
			);
			return;
		}

		try {
			$this->load->library('form_validation');
			$token = explode(' ', $authorizationHeader)[1];
			// Lakukan logika yang sesuai dengan token bearer
			$user = JWT::decode($token, jwtsecretkey);
			$idPelanggan = $user->id_pelanggan;

			$isTransactionAvailable = $this->M_History->check_transaction(
				$idPelanggan,
				$id_transaksi
			);

			if (!$isTransactionAvailable) {
				$this->response(
					[
						'status' => false,
						'message' => 'ID transaksi tidak ditemukan',
					],
					RestController::HTTP_BAD_REQUEST
				);
				return;
			}

			$data = $this->M_History->post_complaint(
				$idPelanggan,
				$id_transaksi,
				$komplen
			);
			if ($data) {
				$this->response(
					[
						'status' => true,
						'message' => 'Komplen berhasil ditambahkan',
					],
					RestController::HTTP_OK
				);
			} else {
				$this->response(
					[
						'status' => false,
						'message' => 'Komplen gagal ditambahkan',
					],
					RestController::HTTP_BAD_REQUEST
				);
			}
		} catch (Exception $e) {
			$this->response(
				[
					'status' => 'gagal',
					'message' => 'Unauthorized',
				],
				RestController::HTTP_UNAUTHORIZED
			);
		}
	}

	public function confirm_payment_post()
	{
		$id_transaksi = $this->input->post('id_transaksi');

		$config['upload_path'] = FCPATH . './img_payment/';
		$config['allowed_types'] = 'jpg|jpeg|png';
		$config['encrypt_name'] = true;
		$config['max_size'] = 5120; // Batasan ukuran gambar (dalam KB)
		$this->load->library('upload', $config);

		if (!$this->upload->do_upload('bukti_bayar')) {
			$response['error'] = $this->upload->display_errors();
			$this->response([
				'status' => false,
				'message' => 'Bukti pembayaran gagal diunggah.'
			], RestController::HTTP_BAD_REQUEST);
		} else {
			$data = $this->upload->data();
			$image_path = $data['file_name'];

			// Simpan path gambar ke dalam kolom bukti_bayar
			$this->M_History->postConfirmPayment($id_transaksi, $image_path);

			$image_url = base_url('img_payment/' . $image_path);
			$response['status'] = true;
			$response['message'] = 'Bukti pembayaran berhasil diunggah.';
			$response['image_url'] = $image_path;
		}

		// Mengembalikan response dalam format JSON
		$this->output
			->set_content_type('application/json')
			->set_output(json_encode($response));
	}
}
