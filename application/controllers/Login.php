<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Login extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_Login');
	}

	public function login_post(){
		$email = $this->post('email');
		$pass = $this->post('password');

		$cek = $this->M_Login->proses_login($email, $pass);

		if($cek->num_rows() == 1){
			$data = array(
				'status' => 'sukses',
				'message' => 'Login berhasil',
				'data' => $cek->row()
			);
		}else{
			$data = array(
				'status' => 'gagal',
				'message' => 'Login gagal',
				'data' => null
			);
		}

		$this->response($data, RestController::HTTP_OK);

	}

	public function alluser_get(){ 

		$data = $this->db->get('pelanggan')->result();

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


	public function pelanggan_get()
    {
        // Mengambil data pelanggan dengan transaksi
        $pelanggan = $this->M_Login->get_pelanggan_with_transaksi();

        // Cek apakah ada data pelanggan
        if ($pelanggan) {
            // $this->response($pelanggan, RestController::HTTP_OK); // Mengirim response sukses dengan data pelanggan
			$this->response([
				'status' => true,
				'message' => 'Data ditemukan',
				'data transaksi' => $pelanggan
			], RestController::HTTP_OK);
        } else {
			//$this->response(NULL, RestController::HTTP_NOT_FOUND); // Mengirim response error jika data tidak ditemukan
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
    }
	
}
