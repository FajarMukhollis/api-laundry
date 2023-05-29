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
                'field' => 'berat',
                'label' => 'Berat',
                'rules' => 'required|numeric'
            ],
            [
                'field' => 'total_harga',
                'label' => 'Total Harga',
                'rules' => 'required'
            ],
            [
                'field' => 'status_bayar',
                'label' => 'Status Bayar',
                'rules' => 'required'
            ],
            // [
            //     'field' => 'status_barang',
            //     'label' => 'Status Barang',
            //     'rules' => 'required'
            // ],
            [
                'field' => 'tgl_order',
                'label' => 'Tanggal Order',
                'rules' => 'required'
            ],
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

    public function get_transaksi()
    {

        // $this->db->where('id_pelanggan', $id_pelanggan);
        // return $this->db->get('transaksi')->result();

        $this->db->select('transaksi.*, pelanggan.nama_pelanggan, produk.nama_produk');
        $this->db->from('transaksi');
        $this->db->join('pelanggan', 'pelanggan.id_pelanggan = transaksi.id_pelanggan');
        $this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
        $query = $this->db->get();

        // Mengembalikan hasil query
        return $query->result();
    }

    public function tambah_transaksi($idPelanggan, $data)
    {
        // Tambahkan data transaksi ke dalam tabel transaksi
        $this->db->insert('transaksi', [
            'id_pelanggan' => $idPelanggan,
            // 'id_petugas' => $data['id_petugas'],
            'id_produk' => $data['id_produk'],
            'berat' => $data['berat'],
            'total_harga' => $data['total_harga'],
            'status_bayar' => $data['status_bayar'],
            // 'status_barang' => $data['status_barang'],
            'tgl_order' => $data['tgl_order'],
            // 'tgl_selesai' => $data['tgl_selesai'],
        ]);
    }
    public function update_transaksi($idPelanggan, $data)
    {
        // Tambahkan data transaksi ke dalam tabel transaksi
        $this->db->where('id_transaksi', $data['id_transaksi']);
        $this->db->update('transaksi', [
            'id_pelanggan' => $idPelanggan,
            'id_petugas' => $data['id_petugas'],
            'id_produk' => $data['id_produk'],
            'berat' => $data['berat'],
            'total_harga' => $data['total_harga'],
            'status_bayar' => $data['status_bayar'],
            'status_barang' => $data['status_barang'],
            'tgl_order' => $data['tgl_order'],
            'tgl_selesai' => $data['tgl_selesai'],
        ]);
    }

    public function getTransaksiByPelanggan($id_pelanggan)
    {
        $this->db->select('transaksi.*, produk.nama_produk');
        $this->db->from('transaksi');
        $this->db->join('produk', 'produk.id_produk = transaksi.id_produk');
        // $this->db->join('petugas', 'petugas.id_petugas = transaksi.id_petugas');
        $this->db->where('transaksi.id_pelanggan', $id_pelanggan);
        $query = $this->db->get();
        return $query->result();
    }

    public function get_id_pelanggan($id_pelanggan)
    {

        $this->db->where('id_pelanggan', $id_pelanggan);
        return $this->db->get('pelanggan')->result();

        // $this->db->select('id_pelanggan');
        // $this->db->from('transaksi');
        // $this->db->where('id_pelanggan', $this->session->userdata('id_pelanggan'));
        // $query = $this->db->get();
        // return $query->result();

    }
}
