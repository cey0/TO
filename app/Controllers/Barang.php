<?php

namespace App\Controllers;

use App\Models\BarangModel;
use CodeIgniter\Controller;

class Barang extends BaseController
{
    protected $barangModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
    }

    public function index()
    {
        $data = [
            'title' => 'Daftar Barang',
            'barang' => $this->barangModel->getBarang()
        ];
        return view('barang/index', $data);
    }

    public function create()
    {
        $data = [
            'title' => 'Tambah Barang',
            'validation' => \Config\Services::validation()
        ];
        return view('barang/create', $data);
    }

    public function save()
    {
        if (!$this->validate([
            'kode_barang' => 'required|is_unique[tbl_barang.kode_barang]',
            'nama_barang' => 'required',
            'harga_net' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'diskon' => 'permit_empty|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'uploaded[gambar]|max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/barang/create')->withInput();
        }

        // Handle file upload
        $fileGambar = $this->request->getFile('gambar');
        $namaGambar = $fileGambar->getRandomName();
        $fileGambar->move('uploads/barang', $namaGambar);

        $this->barangModel->save([
            'kode_barang' => $this->request->getVar('kode_barang'),
            'nama_barang' => $this->request->getVar('nama_barang'),
            'id_jenis' => $this->request->getVar('id_jenis'),
            'harga_net' => $this->request->getVar('harga_net'),
            'harga_jual' => $this->request->getVar('harga_jual'),
            'diskon' => $this->request->getVar('diskon'),
            'stok' => $this->request->getVar('stok'),
            'id_distributor' => $this->request->getVar('id_distributor'),
            'gambar' => $namaGambar
        ]);

        session()->setFlashdata('pesan', 'Data berhasil ditambahkan.');
        return redirect()->to('/barang');
    }

    public function edit($kode_barang)
    {
        $data = [
            'title' => 'Edit Barang',
            'validation' => \Config\Services::validation(),
            'barang' => $this->barangModel->getBarang($kode_barang)
        ];
        return view('barang/edit', $data);
    }

    public function update($kode_barang)
    {
        // Validasi input
        if (!$this->validate([
            'nama_barang' => 'required',
            'harga_net' => 'required|numeric',
            'harga_jual' => 'required|numeric',
            'diskon' => 'permit_empty|numeric',
            'stok' => 'required|numeric',
            'gambar' => 'max_size[gambar,1024]|is_image[gambar]|mime_in[gambar,image/jpg,image/jpeg,image/png]'
        ])) {
            return redirect()->to('/barang/edit/' . $kode_barang)->withInput();
        }

        $fileGambar = $this->request->getFile('gambar');
        if ($fileGambar->getError() == 4) {
            $namaGambar = $this->request->getVar('gambarLama');
        } else {
            $namaGambar = $fileGambar->getRandomName();
            $fileGambar->move('uploads/barang', $namaGambar);
            unlink('uploads/barang/' . $this->request->getVar('gambarLama'));
        }

        $this->barangModel->save([
            'kode_barang' => $kode_barang,
            'nama_barang' => $this->request->getVar('nama_barang'),
            'id_jenis' => $this->request->getVar('id_jenis'),
            'harga_net' => $this->request->getVar('harga_net'),
            'harga_jual' => $this->request->getVar('harga_jual'),
            'diskon' => $this->request->getVar('diskon'),
            'stok' => $this->request->getVar('stok'),
            'id_distributor' => $this->request->getVar('id_distributor'),
            'gambar' => $namaGambar
        ]);

        session()->setFlashdata('pesan', 'Data berhasil diubah.');
        return redirect()->to('/barang');
    }

    public function delete($kode_barang)
    {
        $barang = $this->barangModel->find($kode_barang);
        unlink('uploads/barang/' . $barang['gambar']);
        
        $this->barangModel->delete($kode_barang);
        session()->setFlashdata('pesan', 'Data berhasil dihapus.');
        return redirect()->to('/barang');
    }
} 