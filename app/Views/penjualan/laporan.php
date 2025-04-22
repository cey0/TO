<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-semibold">
                            <i class="fas fa-file-alt me-2"></i>Laporan Penjualan
                        </h5>
                    </div>
                    <div class="card-body">
                        <!-- Filter Tanggal -->
                        <form action="" method="get" class="row mb-4">
                            <div class="col-md-4">
                                <label for="tanggal_awal" class="form-label">Tanggal Awal</label>
                                <input type="date" class="form-control rounded-pill" id="tanggal_awal" name="tanggal_awal" 
                                       value="<?= $tanggal_awal; ?>">
                            </div>
                            <div class="col-md-4">
                                <label for="tanggal_akhir" class="form-label">Tanggal Akhir</label>
                                <input type="date" class="form-control rounded-pill" id="tanggal_akhir" name="tanggal_akhir" 
                                       value="<?= $tanggal_akhir; ?>">
                            </div>
                            <div class="col-md-4 d-flex align-items-end">
                                <button type="submit" class="btn btn-primary rounded-pill me-2 shadow-sm">
                                    <i class="fas fa-search me-1"></i> Filter
                                </button>
                                <button type="button" class="btn btn-success rounded-pill shadow-sm" onclick="window.print()">
                                    <i class="fas fa-print me-1"></i> Cetak
                                </button>
                            </div>
                        </form>

                        <!-- Tabel Laporan -->
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="dataTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Tanggal</th>
                                        <th>Total Item</th>
                                        <th>Total Harga</th>
                                        <th>Pembayaran</th>
                                        <th>Kembalian</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($penjualan as $p) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td><?= date('d/m/Y H:i', strtotime($p['tanggal'])); ?></td>
                                            <td><?= $p['total_item']; ?> item</td>
                                            <td>Rp <?= number_format($p['total_harga'], 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($p['uang_dibayar'], 0, ',', '.'); ?></td>
                                            <td>Rp <?= number_format($p['kembalian'], 0, ',', '.'); ?></td>
                                            <td>
                                                <button type="button" class="btn btn-info btn-sm rounded-pill shadow-sm" 
                                                        onclick="showDetail(<?= $p['id_penjualan']; ?>)">
                                                    <i class="fas fa-eye"></i>
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                                <tfoot>
                                    <tr class="table-primary">
                                        <th colspan="3">Total Penjualan</th>
                                        <th>Rp <?= number_format(array_sum(array_column($penjualan, 'total_harga')), 0, ',', '.'); ?></th>
                                        <th colspan="3"></th>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>

<!-- Modal Detail Penjualan -->
<div class="modal fade" id="modalDetail" tabindex="-1">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Detail Penjualan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th>No</th>
                                <th>Nama Barang</th>
                                <th>Harga Asli</th>
                                <th>Diskon</th>
                                <th>Harga Akhir</th>
                                <th>Jumlah</th>
                                <th>Subtotal</th>
                            </tr>
                        </thead>
                        <tbody id="detailItems">
                            <!-- Data akan diisi dengan JavaScript -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
async function showDetail(id_penjualan) {
    try {
        const response = await fetch(`/penjualan/detail/${id_penjualan}`);
        const data = await response.json();
        
        if (data.status === 'success') {
            let html = '';
            let no = 1;
            
            data.detail.forEach(item => {
                html += `
                    <tr>
                        <td>${no++}</td>
                        <td>${item.nama_barang}</td>
                        <td>Rp ${parseInt(item.harga_asli).toLocaleString('id-ID')}</td>
                        <td>${item.diskon}%</td>
                        <td>Rp ${parseInt(item.harga_satuan).toLocaleString('id-ID')}</td>
                        <td>${item.jumlah}</td>
                        <td>Rp ${(item.harga_satuan * item.jumlah).toLocaleString('id-ID')}</td>
                    </tr>
                `;
            });
            
            document.getElementById('detailItems').innerHTML = html;
            new bootstrap.Modal(document.getElementById('modalDetail')).show();
        } else {
            alert('Gagal memuat detail penjualan');
        }
    } catch (error) {
        alert('Terjadi kesalahan saat memuat detail');
    }
}
</script>