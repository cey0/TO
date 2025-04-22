<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="main-content">
    <div class="container-fluid">
        <div class="row">
            <div class="col-12">
                <div class="d-flex justify-content-between align-items-center mb-4">
                    <h2 class="fw-bold">Daftar Barang</h2>
                    <a href="/barang/create" class="btn btn-primary rounded-pill shadow-sm">
                        <i class="fas fa-plus me-1"></i> Tambah Barang
                    </a>
                </div>
                <?php if (session()->getFlashdata('pesan')) : ?>
                    <div class="alert alert-success rounded-pill px-4 py-2" role="alert">
                        <?= session()->getFlashdata('pesan'); ?>
                    </div>
                <?php endif; ?>
                <div class="card shadow-sm border-0 rounded-4">
                    <div class="card-header bg-primary text-white rounded-top-4 d-flex justify-content-between align-items-center">
                        <span class="fw-semibold">Data Barang</span>
                    </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table class="table table-hover align-middle" id="dataTable">
                                <thead class="table-light">
                                    <tr>
                                        <th>No</th>
                                        <th>Gambar</th>
                                        <th>Kode</th>
                                        <th>Nama Barang</th>
                                        <th>Jenis</th>
                                        <th>Harga Asli</th>
                                        <th>Diskon</th>
                                        <th>Harga Akhir</th>
                                        <th>Stok</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php $i = 1; ?>
                                    <?php foreach ($barang as $b) : ?>
                                        <tr>
                                            <td><?= $i++; ?></td>
                                            <td>
                                                <?php if (isset($b['gambar']) && !empty($b['gambar'])) : ?>
                                                    <img src="/uploads/barang/<?= $b['gambar']; ?>" alt="" class="img-thumbnail rounded-3" width="70">
                                                <?php else : ?>
                                                    <img src="/img/default.jpg" alt="" class="img-thumbnail rounded-3" width="70">
                                                <?php endif; ?>
                                            </td>
                                            <td><?= $b['kode_barang']; ?></td>
                                            <td><?= $b['nama_barang']; ?></td>
                                            <td><?= $b['nama_jenis']; ?></td>
                                            <td>Rp <?= number_format($b['harga_jual'], 0, ',', '.'); ?></td>
                                            <td><?= isset($b['diskon']) ? $b['diskon'] : 0; ?>%</td>
                                            <td>Rp <?= number_format($b['harga_jual'] - ($b['harga_jual'] * (isset($b['diskon']) ? $b['diskon'] : 0) / 100), 0, ',', '.'); ?></td>
                                            <td><?= $b['stok']; ?></td>
                                            <td>
                                                <a href="/barang/edit/<?= $b['kode_barang']; ?>" class="btn btn-warning btn-sm rounded-pill me-1">
                                                    <i class="fas fa-edit"></i>
                                                </a>
                                                <form action="/barang/<?= $b['kode_barang']; ?>" method="post" class="d-inline">
                                                    <?= csrf_field(); ?>
                                                    <input type="hidden" name="_method" value="DELETE">
                                                    <button type="submit" class="btn btn-danger btn-sm rounded-pill" onclick="return confirm('Apakah anda yakin?');">
                                                        <i class="fas fa-trash"></i>
                                                    </button>
                                                </form>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?= $this->endSection(); ?>