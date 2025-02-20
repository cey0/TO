<?php

namespace App\Models;

use CodeIgniter\Model;

class BarangModel extends Model
{
    protected $table = 'tbl_barang';
    protected $primaryKey = 'kode_barang';
    protected $allowedFields = ['kode_barang', 'nama_barang', 'id_jenis', 'harga_net', 'harga_jual', 'diskon', 'stok', 'id_distributor', 'gambar'];
    protected $useTimestamps = false;

    public function getBarang($kode_barang = false)
    {
        if ($kode_barang === false) {
            return $this->select('tbl_barang.*, tbl_jenis.nama_jenis, tbl_distributor.nama_distributor')
                       ->join('tbl_jenis', 'tbl_jenis.kode_jenis = tbl_barang.id_jenis')
                       ->join('tbl_distributor', 'tbl_distributor.id_distributor = tbl_barang.id_distributor')
                       ->findAll();
        }

        return $this->select('tbl_barang.*, tbl_jenis.nama_jenis, tbl_distributor.nama_distributor')
                   ->join('tbl_jenis', 'tbl_jenis.kode_jenis = tbl_barang.id_jenis')
                   ->join('tbl_distributor', 'tbl_distributor.id_distributor = tbl_barang.id_distributor')
                   ->where(['kode_barang' => $kode_barang])
                   ->first();
    }
} 