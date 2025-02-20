<?= $this->extend('layout/template'); ?>

<?= $this->section('content'); ?>
<div class="container mt-4">
    <div class="row justify-content-center">
        <div class="col-md-8">
            <div class="card shadow">
                <div class="card-header bg-primary text-white">
                    <h5 class="mb-0">Tambah Barang Baru</h5>
                </div>
                <div class="card-body">
                    <form action="/barang/save" method="post" enctype="multipart/form-data">
                        <?= csrf_field(); ?>
                        
                        <div class="row mb-3">
                            <label for="kode_barang" class="col-sm-3 col-form-label">Kode Barang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control <?= ($validation->hasError('kode_barang')) ? 'is-invalid' : ''; ?>" 
                                       id="kode_barang" name="kode_barang" value="<?= old('kode_barang'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('kode_barang'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="nama_barang" class="col-sm-3 col-form-label">Nama Barang</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control <?= ($validation->hasError('nama_barang')) ? 'is-invalid' : ''; ?>" 
                                       id="nama_barang" name="nama_barang" value="<?= old('nama_barang'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('nama_barang'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="id_jenis" class="col-sm-3 col-form-label">Jenis Barang</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="id_jenis" name="id_jenis">
                                    <?php foreach ($jenis as $j) : ?>
                                        <option value="<?= $j['kode_jenis']; ?>"><?= $j['nama_jenis']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_net" class="col-sm-3 col-form-label">Harga Net</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control <?= ($validation->hasError('harga_net')) ? 'is-invalid' : ''; ?>" 
                                       id="harga_net" name="harga_net" value="<?= old('harga_net'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('harga_net'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_jual" class="col-sm-3 col-form-label">Harga Jual</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control <?= ($validation->hasError('harga_jual')) ? 'is-invalid' : ''; ?>" 
                                       id="harga_jual" name="harga_jual" value="<?= old('harga_jual'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('harga_jual'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="diskon" class="col-sm-3 col-form-label">Diskon (%)</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control <?= ($validation->hasError('diskon')) ? 'is-invalid' : ''; ?>" 
                                       id="diskon" name="diskon" value="<?= old('diskon', 0); ?>" min="0" max="100">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('diskon'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="harga_akhir" class="col-sm-3 col-form-label">Harga Setelah Diskon</label>
                            <div class="col-sm-9">
                                <input type="text" class="form-control" id="harga_akhir" readonly>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="stok" class="col-sm-3 col-form-label">Stok</label>
                            <div class="col-sm-9">
                                <input type="number" class="form-control <?= ($validation->hasError('stok')) ? 'is-invalid' : ''; ?>" 
                                       id="stok" name="stok" value="<?= old('stok'); ?>">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('stok'); ?>
                                </div>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="id_distributor" class="col-sm-3 col-form-label">Distributor</label>
                            <div class="col-sm-9">
                                <select class="form-select" id="id_distributor" name="id_distributor">
                                    <?php foreach ($distributor as $d) : ?>
                                        <option value="<?= $d['id_distributor']; ?>"><?= $d['nama_distributor']; ?></option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                        </div>

                        <div class="row mb-3">
                            <label for="gambar" class="col-sm-3 col-form-label">Gambar</label>
                            <div class="col-sm-9">
                                <input type="file" class="form-control <?= ($validation->hasError('gambar')) ? 'is-invalid' : ''; ?>" 
                                       id="gambar" name="gambar" onchange="previewImg()">
                                <div class="invalid-feedback">
                                    <?= $validation->getError('gambar'); ?>
                                </div>
                                <div class="col-sm-3 mt-2">
                                    <img src="/img/default.jpg" class="img-thumbnail img-preview">
                                </div>
                            </div>
                        </div>

                        <div class="row mt-4">
                            <div class="col-sm-9 offset-sm-3">
                                <button type="submit" class="btn btn-primary">Simpan</button>
                                <a href="/barang" class="btn btn-secondary">Kembali</a>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Preview gambar
    function previewImg() {
        const gambar = document.querySelector('#gambar');
        const imgPreview = document.querySelector('.img-preview');
        
        const fileGambar = new FileReader();
        fileGambar.readAsDataURL(gambar.files[0]);
        
        fileGambar.onload = function(e) {
            imgPreview.src = e.target.result;
        }
    }

    // Hitung harga setelah diskon
    function hitungHargaAkhir() {
        const hargaJual = parseFloat(document.getElementById('harga_jual').value) || 0;
        const diskon = parseFloat(document.getElementById('diskon').value) || 0;
        const hargaAkhir = hargaJual - (hargaJual * diskon / 100);
        document.getElementById('harga_akhir').value = 'Rp ' + hargaAkhir.toLocaleString('id-ID');
    }

    // Event listeners
    document.getElementById('harga_jual').addEventListener('input', hitungHargaAkhir);
    document.getElementById('diskon').addEventListener('input', hitungHargaAkhir);
</script>

<?= $this->endSection(); ?> 