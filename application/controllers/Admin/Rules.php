<?php
defined('BASEPATH') or exit('No direct script access allowed');

use chriskacerguis\RestServer\RestController;

class Rules extends RestController
{

	public function __construct()
	{
		parent::__construct();
		$this->load->model('M_Admin/M_Rules');
	}

	//rules asosiasi
	public function asosiasi_get()
	{
		$data = $this->M_Rules->get_asosiasi();

		if ($data == null) {
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

	//add asosiasi
	public function asosiasi_post()
	{

		$aturan = $this->put('aturan');

		$proses = $this->M_Rules->add_rules_asosiasi($aturan);

		if ($proses) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil ditambahkan'
			], RestController::HTTP_OK);

			$this->response($data, RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data gagal ditambahkan'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	//edit asosiasi
	public function asosiasi_put()
	{
		$id_rules_asosiasi = $this->put('id_rules_asosiasi');
		$aturan = $this->put('aturan');

		$proses = $this->M_Rules->update_rules_asosiasi($id_rules_asosiasi, $aturan);

		//check id_rules_komplain
		$check_id = $this->M_Rules->cek_idRules_asosiasi($id_rules_asosiasi);
		if ($check_id) {
			if ($proses) {
				$this->response([
					'status' => true,
					'message' => 'Data berhasil diubah'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Data gagal diubah'
				], RestController::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'id_rules_asosiasi tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	//delete asosiasi
	public function asosiasi_delete()
	{
		$id_rules_asosiasi = $this->delete('id_rules_asosiasi');
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$id_rules_asosiasi = $data['id_rules_asosiasi'];

		if ($id_rules_asosiasi === null) {
			$this->response([
				'status' => false,
				'message' => 'id_rules_asosiasi tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
		} else {
			$check_id = $this->M_Rules->get_rules_asosiasi_by_id($id_rules_asosiasi);

			if ($check_id) {
				$this->M_Rules->delete_rules_asosiasi($id_rules_asosiasi);
				$this->response([
					'status' => true,
					'message' => 'Data berhasil dihapus'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'id_rules_asosiasi tidak ditemukan'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}

	//rules komplain
	public function rules_komplain_get()
	{
		$data = $this->M_Rules->get_komplain();

		if ($data == null) {
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

	//add komplain
	public function rules_komplain_post()
	{

		$data = array(
			'aturan' => $this->post('aturan')
		);

		$proses = $this->M_Rules->add_rules_komplain($data);

		if ($proses) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil ditambahkan'
			], RestController::HTTP_CREATED);

			$this->response($data, RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data gagal ditambahkan'
			], RestController::HTTP_BAD_REQUEST);
		}
	}

	//edit komplain
	public function rules_komplain_put()
	{
		$id_rules_komplain = $this->put('id_rules_komplain');
		$aturan = $this->put('aturan');

		$proses = $this->M_Rules->update_rules_komplain($id_rules_komplain, $aturan);

		//check id_rules_komplain
		$check_id = $this->M_Rules->cek_idRules_komplain($id_rules_komplain);
		if ($check_id) {
			if ($proses) {
				$this->response([
					'status' => true,
					'message' => 'Data berhasil diubah'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Data gagal diubah'
				], RestController::HTTP_BAD_REQUEST);
			}
		} else {
			$this->response([
				'status' => false,
				'message' => 'id_rules_komplain tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	//delete komplain
	public function rules_komplain_delete()
	{
		$id_rules_komplain = $this->delete('id_rules_komplain');
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$id_rules_komplain = $data['id_rules_komplain'];

		if ($id_rules_komplain === null) {
			$this->response([
				'status' => false,
				'message' => 'id_rules_komplain tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
		} else {
			$check_id = $this->M_Rules->get_rules_komplain_by_id($id_rules_komplain);

			if ($check_id) {
				$this->M_Rules->delete_rules_komplain($id_rules_komplain);
				$this->response([
					'status' => true,
					'message' => 'Data berhasil dihapus'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'id_rules_komplain tidak ditemukan'
				], RestController::HTTP_BAD_REQUEST);
			}
		}
	}
}
