<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col-md-8">
            <div class="card shadow mb-4">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-shopping-cart me-2"></i>Transaksi Penjualan</h5>
                </div>
                <div class="card-body">
                    <form id="formTransaksi">
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label for="kode_barang">Kode Barang</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="kode_barang" required>
                                    <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalBarang">
                                        <i class="fas fa-search"></i>
                                    </button>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <label for="nama_barang">Nama Barang</label>
                                <input type="text" class="form-control" id="nama_barang" readonly>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label for="harga">Harga</label>
                                <input type="number" class="form-control" id="harga" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="diskon">Diskon (%)</label>
                                <input type="number" class="form-control" id="diskon" readonly>
                            </div>
                            <div class="col-md-4">
                                <label for="jumlah">Jumlah</label>
                                <input type="number" class="form-control" id="jumlah" min="1" value="1">
                            </div>
                        </div>
                        <div class="row">
                            <div class="col-md-12">
                                <button type="submit" class="btn btn-primary">
                                    <i class="fas fa-plus"></i> Tambah
                                </button>
                            </div>
                        </div>
                    </form>

                    <div class="table-responsive mt-4">
                        <table class="table table-bordered table-hover">
                            <thead class="table-light">
                                <tr>
                                    <th>No</th>
                                    <th>Kode</th>
                                    <th>Nama Barang</th>
                                    <th>Harga</th>
                                    <th>Diskon</th>
                                    <th>Jumlah</th>
                                    <th>Subtotal</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody id="detail_transaksi">
                                <!-- Data akan diisi dengan JavaScript -->
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0"><i class="fas fa-money-bill-wave me-2"></i>Pembayaran</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="total" class="form-label">Total</label>
                        <input type="text" class="form-control form-control-lg" id="total" readonly>
                    </div>
                    <div class="mb-3">
                        <label for="bayar" class="form-label">Bayar</label>
                        <input type="number" class="form-control form-control-lg" id="bayar">
                    </div>
                    <div class="mb-3">
                        <label for="kembali" class="form-label">Kembali</label>
                        <input type="text" class="form-control form-control-lg" id="kembali" readonly>
                    </div>
                    <button type="button" class="btn btn-success btn-lg w-100" id="btnSimpan">
                        <i class="fas fa-save me-2"></i>Simpan Transaksi
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <table class="table table-hover" id="tableBarang">
                    <thead>
                        <tr>
                            <th>Kode</th>
                            <th>Nama Barang</th>
                            <th>Harga</th>
                            <th>Diskon</th>
                            <th>Stok</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($barang as $b) : ?>
                            <tr>
                                <td><?= $b['kode_barang']; ?></td>
                                <td><?= $b['nama_barang']; ?></td>
                                <td>Rp <?= number_format($b['harga_jual'], 0, ',', '.'); ?></td>
                                <td><?= isset($b['diskon']) ? $b['diskon'] : 0; ?>%</td>
                                <td><?= $b['stok']; ?></td>
                                <td>
                                    <button type="button" class="btn btn-primary btn-sm" 
                                            onclick="pilihBarang('<?= $b['kode_barang']; ?>', '<?= $b['nama_barang']; ?>', 
                                                               <?= $b['harga_jual']; ?>, <?= isset($b['diskon']) ? $b['diskon'] : 0; ?>)">
                                        <i class="fas fa-check"></i>
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Modal Struk -->
<div class="modal fade" id="modalStruk" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Struk Pembayaran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="struk">
                    <div class="struk-header">
                        <h4>TOKO ANDA</h4>
                        <p>Jl. Contoh No. 123<br>Telp: 081234567890</p>
                        <p>================================</p>
                    </div>
                    <div id="struk-content">
                        <table class="struk-table">
                            <tr>
                                <td>Tanggal</td>
                                <td>: <span id="struk-tanggal"></span></td>
                            </tr>
                            <tr>
                                <td colspan="2">--------------------------------</td>
                            </tr>
                            <tbody id="struk-items">
                                <!-- Items akan diisi dengan JavaScript -->
                            </tbody>
                            <tr>
                                <td colspan="2">--------------------------------</td>
                            </tr>
                            <tr>
                                <td>Total</td>
                                <td>: <span id="struk-total"></span></td>
                            </tr>
                            <tr>
                                <td>Bayar</td>
                                <td>: <span id="struk-bayar"></span></td>
                            </tr>
                            <tr>
                                <td>Kembali</td>
                                <td>: <span id="struk-kembali"></span></td>
                            </tr>
                        </table>
                    </div>
                    <div class="struk-footer">
                        <p>================================</p>
                        <p>Terima Kasih<br>Atas Kunjungan Anda</p>
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-primary" onclick="window.print()">
                    <i class="fas fa-print"></i> Cetak
                </button>
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<script>
let items = [];
let total = 0;

// Format number to currency
function formatRupiah(angka) {
    return 'Rp ' + angka.toLocaleString('id-ID');
}

// Hitung total
function hitungTotal() {
    total = items.reduce((acc, item) => {
        const hargaDiskon = item.harga - (item.harga * item.diskon / 100);
        return acc + (hargaDiskon * item.jumlah);
    }, 0);
    
    document.getElementById('total').value = formatRupiah(total);
}

// Hitung kembalian
document.getElementById('bayar').addEventListener('input', function() {
    const bayar = parseFloat(this.value) || 0;
    const kembali = bayar - total;
    document.getElementById('kembali').value = formatRupiah(kembali);
});

// Cari barang berdasarkan kode
document.getElementById('kode_barang').addEventListener('input', async function() {
    const kode = this.value;
    if (kode) {
        try {
            const response = await fetch(`/penjualan/getBarang?kode_barang=${kode}`);
            const data = await response.json();
            
            if (data.status === 'success') {
                const barang = data.data;
                document.getElementById('nama_barang').value = barang.nama_barang;
                document.getElementById('harga').value = barang.harga_jual;
                document.getElementById('diskon').value = barang.diskon || 0;
            } else {
                document.getElementById('nama_barang').value = '';
                document.getElementById('harga').value = '';
                document.getElementById('diskon').value = '';
            }
        } catch (error) {
            console.error('Error:', error);
        }
    }
});

// Pilih barang dari modal
function pilihBarang(kode, nama, harga, diskon) {
    document.getElementById('kode_barang').value = kode;
    document.getElementById('nama_barang').value = nama;
    document.getElementById('harga').value = harga;
    document.getElementById('diskon').value = diskon;
    
    const modal = bootstrap.Modal.getInstance(document.getElementById('modalBarang'));
    modal.hide();
}

// Tambah item ke keranjang
document.getElementById('formTransaksi').addEventListener('submit', function(e) {
    e.preventDefault();
    
    const kode = document.getElementById('kode_barang').value;
    const nama = document.getElementById('nama_barang').value;
    const harga = parseFloat(document.getElementById('harga').value);
    const diskon = parseFloat(document.getElementById('diskon').value);
    const jumlah = parseInt(document.getElementById('jumlah').value);
    
    if (!nama) {
        alert('Barang tidak ditemukan!');
        return;
    }
    
    // Cek apakah item sudah ada
    const existingItem = items.find(item => item.kode === kode);
    if (existingItem) {
        existingItem.jumlah += jumlah;
    } else {
        items.push({ kode, nama, harga, diskon, jumlah });
    }
    
    renderItems();
    hitungTotal();
    
    // Reset form
    this.reset();
    document.getElementById('nama_barang').value = '';
    document.getElementById('harga').value = '';
    document.getElementById('diskon').value = '';
});

// Render items ke tabel
function renderItems() {
    const tbody = document.getElementById('detail_transaksi');
    tbody.innerHTML = '';
    
    items.forEach((item, index) => {
        const hargaDiskon = item.harga - (item.harga * item.diskon / 100);
        const subtotal = hargaDiskon * item.jumlah;
        
        const tr = document.createElement('tr');
        tr.innerHTML = `
            <td>${index + 1}</td>
            <td>${item.kode}</td>
            <td>${item.nama}</td>
            <td>${formatRupiah(item.harga)}</td>
            <td>${item.diskon}%</td>
            <td>${item.jumlah}</td>
            <td>${formatRupiah(subtotal)}</td>
            <td>
                <button type="button" class="btn btn-danger btn-sm" onclick="hapusItem(${index})">
                    <i class="fas fa-trash"></i>
                </button>
            </td>
        `;
        tbody.appendChild(tr);
    });
}

// Hapus item dari keranjang
function hapusItem(index) {
    items.splice(index, 1);
    renderItems();
    hitungTotal();
}

// Simpan transaksi
document.getElementById('btnSimpan').addEventListener('click', async function() {
    const bayar = parseFloat(document.getElementById('bayar').value) || 0;
    if (items.length === 0) {
        alert('Keranjang masih kosong!');
        return;
    }
    if (bayar < total) {
        alert('Pembayaran kurang!');
        return;
    }
    
    try {
        const response = await fetch('/penjualan/save', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
            },
            body: JSON.stringify({
                total_harga: total,
                uang_dibayar: bayar,
                kembalian: bayar - total,
                items: items
            })
        });
        
        if (response.ok) {
            // Tampilkan struk
            document.getElementById('struk-tanggal').textContent = new Date().toLocaleString('id-ID');
            document.getElementById('struk-total').textContent = formatRupiah(total);
            document.getElementById('struk-bayar').textContent = formatRupiah(bayar);
            document.getElementById('struk-kembali').textContent = formatRupiah(bayar - total);
            
            let strukItems = '';
            items.forEach(item => {
                const hargaDiskon = item.harga - (item.harga * item.diskon / 100);
                const subtotal = hargaDiskon * item.jumlah;
                strukItems += `
                    <tr>
                        <td colspan="2">${item.nama}</td>
                    </tr>
                    <tr>
                        <td>${item.jumlah} x ${formatRupiah(hargaDiskon)}</td>
                        <td align="right">${formatRupiah(subtotal)}</td>
                    </tr>
                `;
            });
            document.getElementById('struk-items').innerHTML = strukItems;
            
            // Tampilkan modal struk
            new bootstrap.Modal(document.getElementById('modalStruk')).show();
            
            // Reset form
            items = [];
            renderItems();
            hitungTotal();
            document.getElementById('bayar').value = '';
            document.getElementById('kembali').value = '';
        } else {
            throw new Error('Gagal menyimpan transaksi');
        }
    } catch (error) {
        alert(error.message);
    }
});

// Inisialisasi DataTable untuk tabel barang
$(document).ready(function() {
    $('#tableBarang').DataTable();
});
</script>
<?= $this->endSection(); ?> 