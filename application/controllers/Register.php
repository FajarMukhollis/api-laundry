<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Register extends RestController {
	
	public function __construct(){
		parent::__construct();
		$this->load->model('M_Register');
	}
	
	public function register_post(){
		if($this->M_Register->cek_email($this->post('email'))->num_rows() == 1){
			$data = array(
				'status' => 'gagal',
				'message' => 'Email sudah terdaftar, gunakan Email lain',
			);
		}else{
			$proses = $this->M_Register->proses_register();
			if($proses){
				$data = array(
					'status' => 'sukses',
					'message' => 'Register berhasil'
				);
			}else{
				$data = array(
					'status' => 'gagal',
					'message' => 'Register gagal'
				);
			}
		}

		$this->response($data, RestController::HTTP_OK);
	}
}
