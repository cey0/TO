<?php

namespace App\Database\Migrations;

use CodeIgniter\Database\Migration;

class CreatePenjualanTables extends Migration
{
    public function up()
    {
        // Tabel penjualan
        $this->forge->addField([
            'id_penjualan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'tanggal' => [
                'type'       => 'DATETIME',
            ],
            'total_harga' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'uang_dibayar' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'kembalian' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_penjualan', true);
        $this->forge->createTable('penjualan');

        // Tabel detail_penjualan
        $this->forge->addField([
            'id_detail_penjualan' => [
                'type'           => 'INT',
                'constraint'     => 11,
                'unsigned'       => true,
                'auto_increment' => true,
            ],
            'id_penjualan' => [
                'type'       => 'INT',
                'constraint' => 11,
                'unsigned'   => true,
            ],
            'kode_barang' => [
                'type'       => 'VARCHAR',
                'constraint' => 20,
            ],
            'jumlah' => [
                'type'       => 'INT',
                'constraint' => 11,
            ],
            'harga_asli' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'harga_satuan' => [
                'type'       => 'DECIMAL',
                'constraint' => '10,2',
            ],
            'diskon' => [
                'type'       => 'DECIMAL',
                'constraint' => '5,2',
                'default'    => 0,
            ],
            'created_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
            'updated_at' => [
                'type' => 'DATETIME',
                'null' => true,
            ],
        ]);
        $this->forge->addKey('id_detail_penjualan', true);
        $this->forge->addForeignKey('id_penjualan', 'penjualan', 'id_penjualan', 'CASCADE', 'CASCADE');
        $this->forge->addForeignKey('kode_barang', 'tbl_barang', 'kode_barang', 'CASCADE', 'CASCADE');
        $this->forge->createTable('detail_penjualan');
    }

    public function down()
    {
        $this->forge->dropTable('detail_penjualan');
        $this->forge->dropTable('penjualan');
    }
} 