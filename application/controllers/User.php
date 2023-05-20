<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class User extends RestController {

		public function __construct(){
		parent::__construct();
		$this->load->model('M_Login');
		$this->load->model('M_Register');
		$this->load->model('M_History');
		$this->load->helper('jwt');

	}


	public function login_post(){
		$jwt = new JWT();
		$jwtsecretkey = 'HS256';

		$email = $this->input->post('email');
		$password = $this->input->post('password');

		$cek = $this->M_Login->proses_login_user($email, $password);
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

	public function register_post(){
		if($this->M_Register->cek_email($this->post('email'))->num_rows() == 1){
			$this -> response([
				'status' => 'gagal',
				'message' => 'Email sudah terdaftar, gunakan Email lain',
			], restController::HTTP_BAD_REQUEST);
		}else{
			$proses = $this->M_Register->proses_register();
			if($proses){
				$this -> response([
					'status' => 'sukses',
					'message' => 'Register berhasil',
				], restController::HTTP_OK);
			}else{
				$this -> response([
					'status' => 'gagal',
					'message' => 'Register gagal'
				], restController::HTTP_FAILED);
			}
		}
	}

	// public function history_get(){
	// 	$id = $this->M_History->get_id_pelanggan($this->get('id_pelanggan'));

	// 	$data = $this->M_History->get_history($id[0]->id_pelanggan);
	// 	if($data){
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

	public function transaksi_get($id_pelanggan) {
        // Verifikasi otentikasi pelanggan di sini
        // Misalnya, Anda dapat menggunakan library JWT untuk verifikasi token

        // Memanggil model untuk mendapatkan data transaksi
        $transaksi = $this->M_History->getTransaksiByPelanggan($id_pelanggan);

        if ($transaksi) {
            $this->response([
                'status' => TRUE,
                'data' => $transaksi
            ], RestController::HTTP_OK);
        } else {
            $this->response([
                'status' => FALSE,
                'message' => 'Data transaksi tidak ditemukan'
            ], RestController::HTTP_NOT_FOUND);
        }
    }

	public function historybyid_get($id_pelanggan) {
		$data = $this->M_History->get_history($id_pelanggan);

		if($data == null){
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data ditemukan',
				'data' => $data
			], RestController::HTTP_OK);
		}
	}

	public function history_get() {
        // Dapatkan data transaksi dari model
        $transaksi = $this->M_History->get_transaksi();

        // Tampilkan data transaksi dalam format JSON
        $this->output->set_content_type('application/json')->set_output(json_encode($transaksi));
    }

	public function history_post() {
        // Dapatkan data transaksi dari request
        $data = array(
            'id_pelanggan' => $this->input->post('id_pelanggan'),
            'id_produk' => $this->input->post('id_produk'),
            // tambahkan data transaksi lainnya sesuai kebutuhan
        );

        // Tambahkan data transaksi ke dalam tabel transaksi
        $this->transaksi_model->tambah_transaksi($data);

        // Tampilkan pesan berhasil
        $this->output->set_content_type('application/json')->set_output(json_encode(array('message' => 'Transaksi berhasil ditambahkan')));
    }


}
