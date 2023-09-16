<?php
defined('BASEPATH') or exit('No direct script access allowed');

class M_History extends CI_Model
{

	public function rules()
	{
		return [
			// [
			//     'field' => 'id_petugas',
			//     'label' => 'ID Petugas',
			//     'rules' => 'required|numeric'
			// ],
			[
				'field' => 'id_produk',
				'label' => 'ID Produk',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'service',
				'label' => 'service',
				'rules' => 'required'
			],
			[
				'field' => 'berat',
				'label' => 'Berat',
				'rules' => 'required|numeric'
			],
			[
				'field' => 'alamat_pelanggan',
				'label' => 'alamat_pelanggan',
				'rules' => 'required'
			],
			[
				'field' => 'total_harga',
				'label' => 'Total Harga',
				'rules' => 'required'
			],
			// [
			//     'field' => 'status_bayar',
			//     'label' => 'Status Bayar',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'status_barang',
			//     'label' => 'Status Barang',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'tgl_order',
			//     'label' => 'Tanggal Order',
			//     'rules' => 'required'
			// ],
			// [
			//     'field' => 'tgl_selesai',
			//     'label' => 'Tanggal Selesai',
			//     'rules' => 'required'
			// ]
		];
	}

	public function history_byid($id_pelanggan)
	{

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

	public function get_history($id_pelanggan)
	{
		$this->db->select('*');
		$this->db->from('pelanggan');
		$this->db->where('pelanggan.id_pelanggan', $id_pelanggan);
		$this->db->join('transaksi', 'pelanggan.id_pelanggan = transaksi.id_pelanggan', 'left');
		$query = $this->db->get();
		return $query->result();
	}

	public function tambah_transaksi($idPelanggan, $data)
	{
		// Tentukan status bayar berdasarkan keberadaan bukti bayar
		if (!isset($data['bukti_bayar']) || $data['bukti_bayar'] === null) {
			$statusBayar = 'Belum Lunas';
		} else {
			$statusBayar = 'Lunas';
		}

		// Tambahkan data transaksi ke dalam tabel transaksi
		$this->db->insert('transaksi', [
			'id_pelanggan' => $idPelanggan,
			'id_petugas' => 1,
			'id_produk' => $data['id_produk'],
			'service' => $data['service'],
			'berat' => $data['berat'],
			'alamat_pelanggan' => $data['alamat_pelanggan'],
			'total_harga' => $data['total_harga'],
			'status_bayar' => $statusBayar,
			'tgl_order' => date('Y-m-d'),
			'komplen' => 'Tidak Ada Komplen',
			// 'tgl_selesai' => $data['tgl_selesai'],
		]);
	}

	public function check_transaction($id_pelanggan, $id_transaksi)
	{
		$this->db->where('id_pelanggan', $id_pelanggan);
		$this->db->where('id_transaksi', $id_transaksi);
		$query = $this->db->get('transaksi');

		return $query->num_rows() > 0;
	}

	public function post_complaint($id_pelanggan, $id_transaksi, $komplen)
{
		$this->db->set('komplen', $komplen);
		$this->db->where('id_pelanggan', $id_pelanggan);
		$this->db->where('id_transaksi', $id_transaksi);
		$this->db->update('transaksi');

		// Mengembalikan status update (berhasil/gagal)
		return $this->db->affected_rows() > 0;
	}

	public function getTransaksiByPelanggan($id_pelanggan)
	{
		$this->db->select('transaksi.*, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		// $this->db->join('petugas', 'petugas.id_petugas = transaksi.id_petugas');
		$this->db->where('transaksi.id_pelanggan', $id_pelanggan);
		$this->db->order_by('transaksi.id_transaksi', 'desc'); // Urutkan berdasarkan id_produk secara descending
		$query = $this->db->get();
		return $query->result();
	}
	

	public function detail_transaction($id_pelanggan, $id_transaksi)
	{
		$this->db->select('transaksi.*, produk.nama_produk');
		$this->db->from('transaksi');
		$this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
		// $this->db->join('petugas', 'petugas.id_petugas = transaksi.id_petugas');
		$this->db->where('transaksi.id_pelanggan', $id_pelanggan);
		$this->db->where('transaksi.id_transaksi', $id_transaksi);

		$query = $this->db->get();
		return $query->row();
	}


	public function postConfirmPayment($id_transaksi, $bukti_bayar)
	{
		$this->db->where('id_transaksi', $id_transaksi);
		$this->db->update('transaksi', array(
			'bukti_bayar' => $bukti_bayar,
			'status_barang' => 'Menunggu Konfirmasi'
		));
		$this->db->affected_rows();
	}

	public function get_id_pelanggan($id_pelanggan)
	{

		$this->db->where('id_pelanggan', $id_pelanggan);
		return $this->db->get('pelanggan')->result();
	}
}
