<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class M_History extends CI_Model {

	public function history_byid($id_pelanggan){

		$this->db->where('id_pelanggan', $id_pelanggan);
        return $this->db->get('transaksi')->result();

	}

	public function get_all_history()
    {
        // Mengambil semua data history transaksi dari database
        $this->db->select('transaksi.*, pelanggan.nama AS nama_pelanggan');
        $this->db->from('transaksi');
        $this->db->join('pelanggan', 'pelanggan.id = transaksi.pelanggan_id');
        $query = $this->db->get();
        return $query->result();
    }

	public function userhistory()
	{
		return $this->db->query("SELECT * FROM pelanggan LEFT JOIN transaksi ON pelanggan.id_pelanggan = transaksi.id_pelanggan;");

	}
}
