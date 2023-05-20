<?php

use chriskacerguis\RestServer\RestController;
require_once APPPATH . 'libraries/REST_Controller.php';

defined('BASEPATH') OR exit('No direct script access allowed');

class History extends RestController {

	public function __construct(){
		parent::__construct();
		$this->load->model('M_History');
	}

	public function history_get($id_pelanggan){ 

		$data = $this->M_History->history_byid($id_pelanggan);

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

	public function historybyid_get(){
		$data['history'] = $this->M_History->get_all_history();

		if($data['history'] == null){
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

	public function userhistory_get(){
		$data = $this->M_History->userhistory();

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

	public function get_history_by_id_pelanggan($id_pelanggan)
    {
        // Mengambil data history transaksi berdasarkan id_pelanggan
        $this->db->where('id_pelanggan', $id_pelanggan);
        $query = $this->db->get('transaksi');
        return $query->result();
    }
}
