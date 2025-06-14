<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/penitipan') ?>">Penitipan</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>

    <?php if (session()->getFlashdata('error')) : ?>
        <div class="alert alert-danger alert-dismissible fade show" role="alert">
            <?= session()->getFlashdata('error') ?>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
        </div>
    <?php endif; ?>

    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Edit Penitipan
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin/penitipan/update/' . $penitipan['kdpenitipan']) ?>" method="post" id="form-penitipan">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-3">Data Penitipan</h5>

                        <div class="mb-3">
                            <label for="kdpenitipan" class="form-label">Kode Penitipan</label>
                            <input type="text" class="form-control" id="kdpenitipan" name="kdpenitipan" value="<?= $penitipan['kdpenitipan'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="tglmasuk" class="form-label">Tanggal Masuk</label>
                            <input type="date" class="form-control" id="tglmasuk" name="tglmasuk" value="<?= date('Y-m-d', strtotime($penitipan['tglpenitipan'])) ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi (Hari)</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" min="1" value="<?= $penitipan['durasi'] ?>" required>
                            <small class="text-muted">Tanggal keluar akan dihitung otomatis</small>
                        </div>

                        <div class="mb-3">
                            <label for="tglkeluar" class="form-label">Tanggal Keluar</label>
                            <input type="date" class="form-control" id="tglkeluar" name="tglkeluar" value="<?= date('Y-m-d', strtotime($penitipan['tglselesai'])) ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="0" <?= $penitipan['status'] == 0 ? 'selected' : '' ?>>Pending</option>
                                <option value="1" <?= $penitipan['status'] == 1 ? 'selected' : '' ?>>Dalam Penitipan</option>
                                <option value="2" <?= $penitipan['status'] == 2 ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Data Pelanggan & Hewan</h5>

                        <div class="mb-3">
                            <label for="pelanggan_nama" class="form-label">Pelanggan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pelanggan_nama" name="pelanggan_nama" value="<?= $pelanggan['nama'] ?>" readonly required>
                                <input type="hidden" id="idpelanggan" name="idpelanggan" value="<?= $pelanggan['idpelanggan'] ?>">
                                <button class="btn btn-primary" type="button" id="btn-pilih-pelanggan">
                                    <i class="bi bi-person-lines-fill"></i> Pilih
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="hewan_nama" class="form-label">Hewan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="hewan_nama" name="hewan_nama" value="<?= $hewan['namahewan'] ?>" readonly required>
                                <input type="hidden" id="idhewan" name="idhewan" value="<?= $hewan['idhewan'] ?>">
                                <button class="btn btn-primary" type="button" id="btn-pilih-hewan">
                                    <i class="bi bi-github"></i> Pilih
                                </button>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="catatan" class="form-label">Catatan Khusus</label>
                            <textarea class="form-control" id="catatan" name="catatan" rows="3"><?= $penitipan['keterangan'] ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Fasilitas Penitipan</h5>

                <div class="mb-3">
                    <button type="button" class="btn btn-success btn-sm" id="btn-tambah-fasilitas">
                        <i class="fas fa-plus"></i> Tambah Fasilitas
                    </button>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="table-fasilitas">
                        <thead class="table-light">
                            <tr>
                                <th width="5%">No</th>
                                <th width="35%">Fasilitas</th>
                                <th width="15%">Harga</th>
                                <th width="10%">Satuan</th>
                                <th width="10%">Jumlah</th>
                                <th width="15%">Subtotal</th>
                                <th width="10%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data fasilitas yang sudah ada -->
                            <?php foreach ($detail_penitipan as $index => $item) : ?>
                                <tr>
                                    <td><?= $index + 1 ?></td>
                                    <td>
                                        <?= $item['namafasilitas'] ?> (<?= $item['kategori'] ?>)
                                        <input type="hidden" name="fasilitas[<?= $index ?>][kdfasilitas]" class="kdfasilitas" value="<?= $item['kdfasilitas'] ?>">
                                    </td>
                                    <td class="text-end">
                                        Rp <?= number_format($item['harga'], 0, ',', '.') ?>
                                        <input type="hidden" name="fasilitas[<?= $index ?>][harga]" class="harga" value="<?= $item['harga'] ?>">
                                    </td>
                                    <td>
                                        <?= $item['satuan'] ?>
                                        <input type="hidden" name="fasilitas[<?= $index ?>][satuan]" value="<?= $item['satuan'] ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm jumlah" name="fasilitas[<?= $index ?>][jumlah]" value="<?= $item['jumlah'] ?>" min="1" required>
                                    </td>
                                    <td class="text-end">
                                        <span class="subtotal-text">Rp <?= number_format($item['totalharga'], 0, ',', '.') ?></span>
                                        <input type="hidden" name="fasilitas[<?= $index ?>][subtotal]" class="subtotal" value="<?= $item['totalharga'] ?>">
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-danger btn-sm btn-hapus-fasilitas">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total Fasilitas:</strong></td>
                                <td class="text-end"><strong id="total-fasilitas">Rp 0</strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">Total Fasilitas:</h6>
                                    <h6 class="mb-0" id="total-fasilitas-summary">Rp 0</h6>
                                </div>
                                <div class="d-flex justify-content-between mb-2">
                                    <h6 class="mb-0">Total Hari:</h6>
                                    <h6 class="mb-0"><span id="total-hari"><?= $penitipan['durasi'] ?></span> Hari</h6>
                                </div>
                                <hr>
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">Total Biaya:</h5>
                                    <h5 class="mb-0" id="total-biaya">Rp <?= number_format($penitipan['grandtotal'], 0, ',', '.') ?></h5>
                                    <input type="hidden" name="total" id="total" value="<?= $penitipan['grandtotal'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= site_url('admin/penitipan') ?>" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Pelanggan -->
<div class="modal fade" id="modalPilihPelanggan" tabindex="-1" aria-labelledby="modalPilihPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihPelangganLabel">Pilih Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tablePelanggan">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>No Hp</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pelanggan_list as $p) : ?>
                                <tr>
                                    <td><?= $p['idpelanggan'] ?></td>
                                    <td><?= $p['nama'] ?></td>
                                    <td><?= $p['nohp'] ?></td>
                                    <td><?= $p['alamat'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan"
                                            data-id="<?= $p['idpelanggan'] ?>"
                                            data-nama="<?= $p['nama'] ?>">
                                            Pilih
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
</div>

<!-- Modal Pilih Hewan -->
<div class="modal fade" id="modalPilihHewan" tabindex="-1" aria-labelledby="modalPilihHewanLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihHewanLabel">Pilih Hewan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableHewan">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nama</th>
                                <th>Jenis</th>
                                <th>Umur</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <!-- Data hewan akan diisi secara dinamis -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Fasilitas -->
<div class="modal fade" id="modalPilihFasilitas" tabindex="-1" aria-labelledby="modalPilihFasilitasLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihFasilitasLabel">Pilih Fasilitas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="tableFasilitas">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Fasilitas</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Satuan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($fasilitas as $f) : ?>
                                <tr>
                                    <td><?= $f['kdfasilitas'] ?></td>
                                    <td><?= $f['namafasilitas'] ?></td>
                                    <td><?= $f['kategori'] ?></td>
                                    <td>Rp <?= number_format($f['harga'], 0, ',', '.') ?></td>
                                    <td><?= $f['satuan'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-fasilitas"
                                            data-id="<?= $f['kdfasilitas'] ?>"
                                            data-nama="<?= $f['namafasilitas'] ?>"
                                            data-kategori="<?= $f['kategori'] ?>"
                                            data-harga="<?= $f['harga'] ?>"
                                            data-satuan="<?= $f['satuan'] ?>">
                                            Pilih
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
</div>
<?= $this->endSection(); ?>

<?= $this->section('scripts'); ?>
<script>
    $(document).ready(function() {
        // Inisialisasi DataTable
        $('#tablePelanggan').DataTable();
        $('#tableHewan').DataTable();
        $('#tableFasilitas').DataTable();

        // Hitung total saat halaman pertama kali dimuat
        hitungTotal();

        // Hitung tanggal keluar berdasarkan durasi
        function hitungTanggalKeluar() {
            let tglMasuk = $('#tglmasuk').val();
            let durasi = parseInt($('#durasi').val()) || 1;

            if (tglMasuk) {
                let date = new Date(tglMasuk);
                date.setDate(date.getDate() + durasi);

                // Format tanggal ke YYYY-MM-DD
                let month = (date.getMonth() + 1).toString().padStart(2, '0');
                let day = date.getDate().toString().padStart(2, '0');
                let tglKeluar = `${date.getFullYear()}-${month}-${day}`;

                $('#tglkeluar').val(tglKeluar);
                $('#total-hari').text(durasi);
                hitungTotal();
            }
        }

        // Hitung tanggal keluar saat halaman dimuat
        hitungTanggalKeluar();

        // Hitung tanggal keluar saat durasi atau tanggal masuk berubah
        $('#tglmasuk, #durasi').on('change', function() {
            hitungTanggalKeluar();
        });

        // Modal Pilih Pelanggan
        $('#btn-pilih-pelanggan').on('click', function() {
            $('#modalPilihPelanggan').modal('show');
        });

        // Pilih Pelanggan
        $(document).on('click', '.btn-pilih-pelanggan', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#idpelanggan').val(id);
            $('#pelanggan_nama').val(nama);
            $('#modalPilihPelanggan').modal('hide');

            // Reset hewan
            $('#idhewan').val('');
            $('#hewan_nama').val('');

            // Enable tombol pilih hewan
            $('#btn-pilih-hewan').prop('disabled', false);

            // Load data hewan berdasarkan pelanggan
            loadHewanByPelanggan(id);
        });

        // Load hewan berdasarkan pelanggan
        function loadHewanByPelanggan(idPelanggan) {
            $.ajax({
                url: '<?= site_url('admin/penitipan/getHewanByPelanggan/') ?>' + idPelanggan,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    $('#tableHewan tbody').empty();

                    if (response.status === 'success' && response.data.length > 0) {
                        $.each(response.data, function(index, hewan) {
                            let row = `
                                <tr>
                                    <td>${hewan.idhewan}</td>
                                    <td>${hewan.namahewan}</td>
                                    <td>${hewan.jenis}</td>
                                    <td>${hewan.umur} ${hewan.satuan_umur}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-hewan" 
                                            data-id="${hewan.idhewan}" 
                                            data-nama="${hewan.namahewan}">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>
                            `;
                            $('#tableHewan tbody').append(row);
                        });
                    } else {
                        $('#tableHewan tbody').append('<tr><td colspan="5" class="text-center">Tidak ada data hewan</td></tr>');
                    }
                },
                error: function() {
                    $('#tableHewan tbody').empty();
                    $('#tableHewan tbody').append('<tr><td colspan="5" class="text-center">Gagal memuat data hewan</td></tr>');
                }
            });
        }

        // Modal Pilih Hewan
        $('#btn-pilih-hewan').on('click', function() {
            if ($('#idpelanggan').val()) {
                $('#modalPilihHewan').modal('show');
            } else {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Pilih pelanggan terlebih dahulu!'
                });
            }
        });

        // Pilih Hewan
        $(document).on('click', '.btn-pilih-hewan', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');

            $('#idhewan').val(id);
            $('#hewan_nama').val(nama);
            $('#modalPilihHewan').modal('hide');
        });

        // Modal Pilih Fasilitas
        $('#btn-tambah-fasilitas').on('click', function() {
            $('#modalPilihFasilitas').modal('show');
        });

        // Pilih Fasilitas
        $(document).on('click', '.btn-pilih-fasilitas', function() {
            let id = $(this).data('id');
            let nama = $(this).data('nama');
            let kategori = $(this).data('kategori');
            let harga = $(this).data('harga');
            let satuan = $(this).data('satuan');

            // Cek apakah fasilitas sudah ada di tabel
            let fasilitasExists = false;
            $('#table-fasilitas tbody tr').each(function() {
                let existingId = $(this).find('.kdfasilitas').val();
                if (existingId === id) {
                    fasilitasExists = true;
                    // Tambah jumlah jika sudah ada
                    let jumlahInput = $(this).find('.jumlah');
                    let jumlah = parseInt(jumlahInput.val()) + 1;
                    jumlahInput.val(jumlah);

                    // Update subtotal
                    let subtotalInput = $(this).find('.subtotal');
                    let subtotal = harga * jumlah;
                    subtotalInput.val(subtotal);
                    $(this).find('.subtotal-text').text('Rp ' + subtotal.toLocaleString('id-ID'));

                    hitungTotal();
                    return false;
                }
            });

            if (!fasilitasExists) {
                // Tambahkan fasilitas baru ke tabel
                let rowCount = $('#table-fasilitas tbody tr').length;
                let newRow = `
                    <tr>
                        <td>${rowCount + 1}</td>
                        <td>
                            ${nama} (${kategori})
                            <input type="hidden" name="fasilitas[${rowCount}][kdfasilitas]" class="kdfasilitas" value="${id}">
                        </td>
                        <td class="text-end">
                            Rp ${harga.toLocaleString('id-ID')}
                            <input type="hidden" name="fasilitas[${rowCount}][harga]" class="harga" value="${harga}">
                        </td>
                        <td>
                            ${satuan}
                            <input type="hidden" name="fasilitas[${rowCount}][satuan]" value="${satuan}">
                        </td>
                        <td>
                            <input type="number" class="form-control form-control-sm jumlah" name="fasilitas[${rowCount}][jumlah]" value="1" min="1" required>
                        </td>
                        <td class="text-end">
                            <span class="subtotal-text">Rp ${harga.toLocaleString('id-ID')}</span>
                            <input type="hidden" name="fasilitas[${rowCount}][subtotal]" class="subtotal" value="${harga}">
                        </td>
                        <td>
                            <button type="button" class="btn btn-danger btn-sm btn-hapus-fasilitas">
                                <i class="bi bi-trash"></i>
                            </button>
                        </td>
                    </tr>
                `;

                $('#table-fasilitas tbody').append(newRow);
                hitungTotal();
            }

            $('#modalPilihFasilitas').modal('hide');
        });

        // Hapus Fasilitas
        $(document).on('click', '.btn-hapus-fasilitas', function() {
            $(this).closest('tr').remove();

            // Renumber rows
            $('#table-fasilitas tbody tr').each(function(index) {
                $(this).find('td:first').text(index + 1);

                // Update name attributes
                $(this).find('.kdfasilitas').attr('name', 'fasilitas[' + index + '][kdfasilitas]');
                $(this).find('.harga').attr('name', 'fasilitas[' + index + '][harga]');
                $(this).find('.jumlah').attr('name', 'fasilitas[' + index + '][jumlah]');
                $(this).find('.subtotal').attr('name', 'fasilitas[' + index + '][subtotal]');
            });

            hitungTotal();
        });

        // Update subtotal saat jumlah berubah
        $(document).on('change', '.jumlah', function() {
            let row = $(this).closest('tr');
            let harga = parseFloat(row.find('.harga').val()) || 0;
            let jumlah = parseFloat($(this).val()) || 1;
            let subtotal = harga * jumlah;

            row.find('.subtotal').val(subtotal);
            row.find('.subtotal-text').text('Rp ' + subtotal.toLocaleString('id-ID'));

            hitungTotal();
        });

        // Hitung total biaya
        function hitungTotal() {
            let totalFasilitas = 0;
            let durasi = parseInt($('#durasi').val()) || 1;

            $('.subtotal').each(function() {
                let subtotal = parseFloat($(this).val()) || 0;
                totalFasilitas += subtotal;
            });

            let totalBiaya = totalFasilitas * durasi;

            $('#total-fasilitas').text('Rp ' + totalFasilitas.toLocaleString('id-ID'));
            $('#total-fasilitas-summary').text('Rp ' + totalFasilitas.toLocaleString('id-ID'));
            $('#total-biaya').text('Rp ' + totalBiaya.toLocaleString('id-ID'));
            $('#total').val(totalBiaya);
        }

        // Validasi form sebelum submit
        $('#form-penitipan').on('submit', function(e) {
            e.preventDefault();
            let valid = true;

            if (!$('#idpelanggan').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Pelanggan harus dipilih'
                });
                valid = false;
                return false;
            }

            if (!$('#idhewan').val()) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Hewan harus dipilih'
                });
                valid = false;
                return false;
            }

            let fasilitasCount = $('#table-fasilitas tbody tr').length;
            if (fasilitasCount === 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Minimal harus ada satu fasilitas yang dipilih'
                });
                valid = false;
                return false;
            }

            // Jika validasi berhasil, kirim data dengan AJAX
            if (valid) {
                $.ajax({
                    url: $(this).attr('action'),
                    type: 'POST',
                    data: $(this).serialize(),
                    dataType: 'json',
                    beforeSend: function() {
                        // Tampilkan loading
                        Swal.fire({
                            title: 'Memproses...',
                            text: 'Mohon tunggu sebentar',
                            allowOutsideClick: false,
                            allowEscapeKey: false,
                            showConfirmButton: false,
                            didOpen: () => {
                                Swal.showLoading();
                            }
                        });
                    },
                    success: function(response) {
                        if (response.status === 'success') {
                            Swal.fire({
                                icon: 'success',
                                title: 'Berhasil',
                                text: response.message,
                                showCancelButton: true,
                                confirmButtonText: 'Cetak Faktur',
                                cancelButtonText: 'Kembali ke Daftar',
                                reverseButtons: true
                            }).then((result) => {
                                if (result.isConfirmed) {
                                    // Buka halaman cetak faktur di tab baru
                                    window.open(response.data.print_url, '_blank');
                                    // Redirect ke halaman daftar penitipan
                                    window.location.href = '<?= site_url('admin/penitipan') ?>';
                                } else {
                                    // Redirect ke halaman daftar penitipan
                                    window.location.href = '<?= site_url('admin/penitipan') ?>';
                                }
                            });
                        } else {
                            Swal.fire({
                                icon: 'error',
                                title: 'Error',
                                text: response.message || 'Terjadi kesalahan saat menyimpan data'
                            });
                        }
                    },
                    error: function(xhr, status, error) {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: 'Terjadi kesalahan: ' + error
                        });
                    }
                });
            }
        });

        // Load data hewan jika pelanggan sudah dipilih saat halaman dimuat
        if ($('#idpelanggan').val()) {
            loadHewanByPelanggan($('#idpelanggan').val());
        }
    });
</script>
<?= $this->endSection(); ?>