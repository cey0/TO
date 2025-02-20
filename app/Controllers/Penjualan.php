<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use CodeIgniter\API\ResponseTrait;

class Penjualan extends BaseController
{
    use ResponseTrait;
    
    protected $barangModel;
    protected $penjualanModel;
    protected $detailPenjualanModel;
    protected $db;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->penjualanModel = new PenjualanModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $data = [
            'title' => 'Transaksi Penjualan',
            'barang' => $this->barangModel->getBarang()
        ];
        return view('penjualan/index', $data);
    }

    public function getBarang()
    {
        $kode_barang = $this->request->getGet('kode_barang');
        $barang = $this->barangModel->getBarang($kode_barang);
        
        if ($barang) {
            return $this->respond([
                'status' => 'success',
                'data' => $barang
            ]);
        }
        
        return $this->respond([
            'status' => 'error',
            'message' => 'Barang tidak ditemukan'
        ], 404);
    }

    public function getAllBarang()
    {
        $barang = $this->barangModel->getBarang();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $barang
        ]);
    }

    public function save()
    {
        try {
            $json = $this->request->getJSON();
            
            // Validasi input
            if (empty($json->items)) {
                throw new \Exception('Tidak ada barang yang dipilih');
            }
            
            // Mulai transaksi database
            $this->db->transStart();
            
            // Simpan data penjualan
            $penjualan = [
                'tanggal' => date('Y-m-d H:i:s'),
                'total_harga' => $json->total_harga,
                'uang_dibayar' => $json->uang_dibayar,
                'kembalian' => $json->kembalian
            ];
            
            if (!$this->penjualanModel->insert($penjualan)) {
                throw new \Exception('Gagal menyimpan data penjualan');
            }
            
            $id_penjualan = $this->penjualanModel->insertID();
            
            // Simpan detail penjualan
            foreach ($json->items as $item) {
                // Cek stok
                $barang = $this->barangModel->find($item->kode);
                if (!$barang) {
                    throw new \Exception("Barang dengan kode {$item->kode} tidak ditemukan");
                }
                if ($barang['stok'] < $item->jumlah) {
                    throw new \Exception("Stok barang {$barang['nama_barang']} tidak mencukupi");
                }
                
                $detail = [
                    'id_penjualan' => $id_penjualan,
                    'kode_barang' => $item->kode,
                    'jumlah' => $item->jumlah,
                    'harga_asli' => $item->harga,
                    'harga_satuan' => $item->harga - ($item->harga * $item->diskon / 100),
                    'diskon' => $item->diskon
                ];
                
                if (!$this->detailPenjualanModel->insert($detail)) {
                    throw new \Exception('Gagal menyimpan detail penjualan');
                }
                
                // Update stok barang
                $stok_baru = $barang['stok'] - $item->jumlah;
                if (!$this->barangModel->update($item->kode, ['stok' => $stok_baru])) {
                    throw new \Exception('Gagal mengupdate stok barang');
                }
            }
            
            // Commit transaksi jika semua berhasil
            $this->db->transComplete();
            
            if ($this->db->transStatus() === false) {
                throw new \Exception('Gagal melakukan transaksi');
            }
            
            return $this->respond([
                'status' => 'success',
                'message' => 'Transaksi berhasil disimpan'
            ]);
            
        } catch (\Exception $e) {
            // Rollback transaksi jika terjadi error
            if (isset($this->db) && $this->db->transStatus() !== null) {
                $this->db->transRollback();
            }
            
            log_message('error', '[Penjualan::save] ' . $e->getMessage());
            
            return $this->respond([
                'status' => 'error',
                'message' => $e->getMessage()
            ], 500);
        }
    }

    public function getDetailPenjualan($id_penjualan)
    {
        $detail = $this->detailPenjualanModel->getDetailWithBarang($id_penjualan);
        
        if ($detail) {
            return $this->respond([
                'status' => 'success',
                'data' => $detail
            ]);
        }
        
        return $this->respond([
            'status' => 'error',
            'message' => 'Detail penjualan tidak ditemukan'
        ], 404);
    }

    public function laporan()
    {
        $tanggal_awal = $this->request->getGet('tanggal_awal') ?? date('Y-m-d');
        $tanggal_akhir = $this->request->getGet('tanggal_akhir') ?? date('Y-m-d');
        
        $data = [
            'title' => 'Laporan Penjualan',
            'penjualan' => $this->penjualanModel->getLaporanPenjualan($tanggal_awal, $tanggal_akhir),
            'tanggal_awal' => $tanggal_awal,
            'tanggal_akhir' => $tanggal_akhir
        ];
        
        return view('penjualan/laporan', $data);
    }
} 