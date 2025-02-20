<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row">
        <div class="col">
            <h2 class="mb-4">Daftar Barang</h2>
            <?php if (session()->getFlashdata('pesan')) : ?>
                <div class="alert alert-success" role="alert">
                    <?= session()->getFlashdata('pesan'); ?>
                </div>
            <?php endif; ?>
            <div class="card shadow">
                <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                    <span>Data Barang</span>
                    <a href="/barang/create" class="btn btn-light btn-sm">
                        <i class="fas fa-plus"></i> Tambah Barang
                    </a>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-hover" id="dataTable">
                            <thead>
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
                                                <img src="/uploads/barang/<?= $b['gambar']; ?>" alt="" class="img-thumbnail" width="100">
                                            <?php else : ?>
                                                <img src="/img/default.jpg" alt="" class="img-thumbnail" width="100">
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
                                            <a href="/barang/edit/<?= $b['kode_barang']; ?>" class="btn btn-warning btn-sm">
                                                <i class="fas fa-edit"></i>
                                            </a>
                                            <form action="/barang/<?= $b['kode_barang']; ?>" method="post" class="d-inline">
                                                <?= csrf_field(); ?>
                                                <input type="hidden" name="_method" value="DELETE">
                                                <button type="submit" class="btn btn-danger btn-sm" onclick="return confirm('Apakah anda yakin?');">
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
<?= $this->endSection(); ?> 