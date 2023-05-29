<?php
defined('BASEPATH') OR exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Transaksi extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_Transaksi');
	}

	public function transaksi_get(){ 

		$data = $this->db->get('transaksi')->result();

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



	// public function transaksibyid_get(){
	// 	$id = $this->get('id_transaksi');

	// 	$data = $this->db->get_where('transaksi', ['id_transaksi' => $id])->result();

	// 	if($data == null){
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
