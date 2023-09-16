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
		$this->load->model('M_Admin/M_Login');
		$this->load->model('M_Admin/M_Transaksi');
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
		$transaksi = $this->M_Transaksi->get_transaksi();

		// Tampilkan data transaksi dalam format JSON
		if ($transaksi == null) {
			$this->response([
				'status' => false,
				'message' => 'Data Transaksi Kosong'
			], restController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data Transaksi Ditemukan',
				'data' => $transaksi
			], restController::HTTP_OK);
		}
		$this->output->set_content_type('application/json')->set_output(json_encode($transaksi));
	}

	function history_delete()
	{
		$id_transaksi = $this->delete('id_transaksi');
		$raw = $this->input->raw_input_stream;
		$data = json_decode($raw, true);
		$id_transaksi = $data['id_transaksi'];

		if ($id_transaksi === null) {
			$this->response([
				'status' => false,
				'message' => 'id Transaksi tidak ditemukan'
			], RestController::HTTP_BAD_REQUEST);
		} else {
			// Pengecekan apakah transaksi dengan ID tersebut ada dalam database
			$transaksi = $this->M_Transaksi->get_transaksi_by_id($id_transaksi);
			if ($transaksi) {
				// Hapus produk
				$this->M_Transaksi->delete_transaksi($id_transaksi);
				$this->response([
					'status' => true,
					'message' => 'Transaksi berhasil dihapus'
				], RestController::HTTP_OK);
			} else {
				$this->response([
					'status' => false,
					'message' => 'Gagal menghapus Transaksi'
				], RestController::HTTP_NOT_FOUND);
			}
		}
	}

	public function history_put()
	{
		$id_transaksi = $this->put('id_transaksi');
		$status_bayar = $this->put('status_bayar');
		$status_barang = $this->put('status_barang');
		$tgl_selesai = $this->put('tgl_selesai');

		$check_data = $this->M_Transaksi->update_transaksi($id_transaksi, $status_bayar, $status_barang, $tgl_selesai);
		if ($check_data) {
			$this->response([
				'status' => true,
				'message' => 'Data berhasil diupdate'
			], RestController::HTTP_OK);
		} else {
			$this->response([
				'status' => false,
				'message' => 'Data tidak ditemukan'
			], RestController::HTTP_NOT_FOUND);
		}
	}

	public function history_byWeek_get()
	{
		$dataOneWeek = $this->M_Transaksi->get_transaksi_by_one_week();

		if ($dataOneWeek == null) {
			$this->response([
				'status' => false,
				'message' => 'Data Transaksi Dalam 7 Hari Terakhir Tidak Ditemukan'
			], restController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data Transaksi Dalam 7 Hari Terakhir Ditemukan',
				'data' => $dataOneWeek
			], restController::HTTP_OK);
		}
	}

	public function history_bymonth_get()
	{
		$dataOneMonth = $this->M_Transaksi->get_trasaksi_by_one_month();

		if ($dataOneMonth == null) {
			$this->response([
				'status' => false,
				'message' => 'Data Transaksi Dalam 30 Hari Terakhir Tidak Ditemukan'
			], restController::HTTP_NOT_FOUND);
		} else {
			$this->response([
				'status' => true,
				'message' => 'Data Transaksi Dalam 30 Hari Terakhir Ditemukan',
				'data' => $dataOneMonth
			], restController::HTTP_OK);
		}
	}

	public function detail_transaksi_get($id_transaksi) {

        $transaksi = $this->M_Transaksi->getDetailTransaksi($id_transaksi);

        if ($transaksi != null) {
            $this->response([
				'status' => true,
				'message' => 'Detail Transaksi Ditemukan',
				'data' => $transaksi
			], restController::HTTP_OK);
        } else {
            $this->response([
				'status' => false,
				'message' => 'Detail Transaksi Tidak Ditemukan'
			], restController::HTTP_NOT_FOUND);
        }
    }
}
