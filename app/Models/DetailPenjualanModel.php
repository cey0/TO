<?php

namespace App\Models;

use CodeIgniter\Model;

class DetailPenjualanModel extends Model
{
    protected $table = 'detail_penjualan';
    protected $primaryKey = 'id_detail_penjualan';
    protected $useAutoIncrement = true;
    protected $returnType = 'array';
    protected $allowedFields = ['id_penjualan', 'kode_barang', 'jumlah', 'harga_asli', 'harga_satuan', 'diskon'];
    protected $useTimestamps = false;

    protected $validationRules = [
        'id_penjualan' => 'required|numeric',
        'kode_barang' => 'required',
        'jumlah' => 'required|numeric',
        'harga_asli' => 'required|numeric',
        'harga_satuan' => 'required|numeric',
        'diskon' => 'permit_empty|numeric'
    ];

    public function getDetailWithBarang($id_penjualan)
    {
        return $this->select('detail_penjualan.*, tbl_barang.nama_barang')
            ->join('tbl_barang', 'tbl_barang.kode_barang = detail_penjualan.kode_barang')
            ->where('id_penjualan', $id_penjualan)
            ->findAll();
    }
} 