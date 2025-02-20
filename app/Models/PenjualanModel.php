<?php

namespace App\Models;

use CodeIgniter\Model;

class PenjualanModel extends Model
{
    protected $table = 'penjualan';
    protected $primaryKey = 'id_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['tanggal', 'total_harga', 'uang_dibayar', 'kembalian'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'total_harga' => 'required|numeric',
        'uang_dibayar' => 'required|numeric',
        'kembalian' => 'required|numeric'
    ];

    public function getDetailPenjualan($id_penjualan)
    {
        return $this->db->table('detail_penjualan')
            ->join('tbl_barang', 'tbl_barang.kode_barang = detail_penjualan.kode_barang')
            ->where('id_penjualan', $id_penjualan)
            ->get()
            ->getResultArray();
    }

    public function getLaporanPenjualan($tanggal_awal, $tanggal_akhir)
    {
        return $this->select('penjualan.*, COUNT(detail_penjualan.id_detail_penjualan) as total_item')
            ->join('detail_penjualan', 'detail_penjualan.id_penjualan = penjualan.id_penjualan')
            ->where('DATE(tanggal) >=', $tanggal_awal)
            ->where('DATE(tanggal) <=', $tanggal_akhir)
            ->groupBy('penjualan.id_penjualan')
            ->findAll();
    }
} 