<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content'); ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/perawatan') ?>">Perawatan</a></li>
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
            Form Edit Perawatan
        </div>
        <div class="card-body">
            <form action="<?= site_url('admin/perawatan/update/' . $perawatan['kdperawatan']) ?>" method="post" id="form-perawatan">
                <?= csrf_field() ?>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <h5 class="mb-3">Data Perawatan</h5>

                        <div class="mb-3">
                            <label for="kdperawatan" class="form-label">Kode Perawatan</label>
                            <input type="text" class="form-control" id="kdperawatan" name="kdperawatan" value="<?= $perawatan['kdperawatan'] ?>" readonly>
                        </div>

                        <div class="mb-3">
                            <label for="tglperawatan" class="form-label">Tanggal Perawatan</label>
                            <input type="date" class="form-control" id="tglperawatan" name="tglperawatan" value="<?= $perawatan['tglperawatan'] ?>" required>
                        </div>

                        <div class="mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status" required>
                                <option value="0" <?= $perawatan['status'] == 0 ? 'selected' : '' ?>>Menunggu</option>
                                <option value="1" <?= $perawatan['status'] == 1 ? 'selected' : '' ?>>Dalam Proses</option>
                                <option value="2" <?= $perawatan['status'] == 2 ? 'selected' : '' ?>>Selesai</option>
                            </select>
                        </div>
                    </div>

                    <div class="col-md-6">
                        <h5 class="mb-3">Data Pelanggan & Hewan</h5>

                        <div class="mb-3">
                            <label for="pelanggan_nama" class="form-label">Pelanggan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="pelanggan_nama" name="pelanggan_nama" value="<?= $pelanggan['nama'] ?? 'Pelanggan Umum' ?>" readonly>
                                <input type="hidden" id="idpelanggan" name="idpelanggan" value="<?= $perawatan['idpelanggan'] ?>">
                                <button class="btn btn-primary" type="button" id="btn-pilih-pelanggan">
                                    <i class="bi bi-person-lines-fill"></i> Pilih
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="btn-reset-pelanggan">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>

                        </div>

                        <div class="mb-3">
                            <label for="hewan_nama" class="form-label">Hewan</label>
                            <div class="input-group">
                                <input type="text" class="form-control" id="hewan_nama" name="hewan_nama" value="<?= $perawatan['namahewan'] ?? '' ?>" readonly>
                                <input type="hidden" id="idhewan" name="idhewan" value="<?= $perawatan['idhewan'] ?>">
                                <button class="btn btn-primary" type="button" id="btn-pilih-hewan" <?= empty($perawatan['idpelanggan']) ? 'disabled' : '' ?>>
                                    <i class="bi bi-github"></i> Pilih
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="btn-reset-hewan">
                                    <i class="bi bi-x-circle"></i>
                                </button>
                            </div>
                            <small class="text-muted" id="hewan-info">
                                <?= empty($perawatan['idpelanggan']) ? 'Pilih pelanggan terlebih dahulu' : '' ?>
                            </small>
                        </div>

                        <div class="mb-3">
                            <label for="keterangan" class="form-label">Keterangan</label>
                            <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= $perawatan['keterangan'] ?></textarea>
                        </div>
                    </div>
                </div>

                <hr>

                <h5 class="mb-3">Fasilitas Perawatan</h5>

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
                            <?php $no = 1;
                            foreach ($detail_perawatan as $detail) : ?>
                                <tr>
                                    <td><?= $no++ ?></td>
                                    <td>
                                        <?= $detail['namafasilitas'] ?>
                                        <input type="hidden" name="detailkdfasilitas[]" value="<?= $detail['detailkdfasilitas'] ?>">
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm harga" name="harga[]" value="<?= $detail['harga'] ?>" readonly>
                                    </td>
                                    <td><?= $detail['satuan'] ?></td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm jumlah" name="jumlah[]" value="<?= $detail['jumlah'] ?>" min="1" required>
                                    </td>
                                    <td>
                                        <input type="number" class="form-control form-control-sm totalharga" name="totalharga[]" value="<?= $detail['totalharga'] ?>" readonly>
                                    </td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-danger btn-hapus-fasilitas">
                                            <i class="bi bi-trash"></i>
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <td colspan="5" class="text-end"><strong>Total:</strong></td>
                                <td class="text-end"><strong id="total-fasilitas">Rp <?= number_format($perawatan['grandtotal'], 0, ',', '.') ?></strong></td>
                                <td></td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6 offset-md-6">
                        <div class="card">
                            <div class="card-body">
                                <div class="d-flex justify-content-between">
                                    <h5 class="mb-0">Total Biaya:</h5>
                                    <h5 class="mb-0" id="total-biaya">Rp <?= number_format($perawatan['grandtotal'], 0, ',', '.') ?></h5>
                                    <input type="hidden" name="grandtotal" id="grandtotal" value="<?= $perawatan['grandtotal'] ?>">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="d-flex justify-content-end mt-4">
                    <a href="<?= site_url('admin/perawatan') ?>" class="btn btn-secondary me-2">Batal</a>
                    <button type="submit" class="btn btn-primary">Simpan</button>
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
                            <?php foreach ($hewan_list as $h) : ?>
                                <tr>
                                    <td><?= $h['idhewan'] ?></td>
                                    <td><?= $h['namahewan'] ?></td>
                                    <td><?= $h['jenis'] ?></td>
                                    <td><?= $h['umur'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-hewan"
                                            data-id="<?= $h['idhewan'] ?>"
                                            data-nama="<?= $h['namahewan'] ?>">
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

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize DataTables
        $('#tablePelanggan, #tableFasilitas').DataTable();

        // Format currency
        function formatRupiah(angka) {
            return 'Rp ' + parseFloat(angka).toFixed(0).replace(/\d(?=(\d{3})+$)/g, '$&.');
        }

        // Calculate total
        function calculateTotal() {
            let total = 0;
            $('.totalharga').each(function() {
                total += parseFloat($(this).val() || 0);
            });

            $('#total-fasilitas').text(formatRupiah(total));
            $('#total-biaya').text(formatRupiah(total));
            $('#grandtotal').val(total);
        }

        // Handle form submission with AJAX
        $('#form-perawatan').submit(function(e) {
            e.preventDefault();

            // Show loading indicator
            Swal.fire({
                title: 'Menyimpan...',
                text: 'Mohon tunggu sebentar',
                allowOutsideClick: false,
                didOpen: () => {
                    Swal.showLoading();
                }
            });

            // Get form data
            const formData = new FormData(this);

            // Send AJAX request
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: formData,
                processData: false,
                contentType: false,
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            timer: 1500,
                            showConfirmButton: false
                        }).then(() => {
                            // Redirect to detail page
                            window.location.href = '<?= site_url('admin/perawatan/detail/') ?>' + '<?= $perawatan['kdperawatan'] ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Gagal',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: 'Terjadi kesalahan saat menyimpan data'
                    });
                    console.error(xhr.responseText);
                }
            });
        });

        // Handle quantity change
        $(document).on('change', '.jumlah', function() {
            const row = $(this).closest('tr');
            const harga = parseFloat(row.find('.harga').val() || 0);
            const jumlah = parseFloat($(this).val() || 0);
            const total = harga * jumlah;

            row.find('.totalharga').val(total);
            calculateTotal();
        });

        // Handle delete facility button
        $(document).on('click', '.btn-hapus-fasilitas', function() {
            $(this).closest('tr').remove();
            calculateTotal();
        });

        // Handle pelanggan umum checkbox
        $('#pelanggan_umum').change(function() {
            if ($(this).is(':checked')) {
                $('#idpelanggan').val('');
                $('#pelanggan_nama').val('Pelanggan Umum');
                $('#btn-pilih-pelanggan').prop('disabled', true);
                $('#btn-pilih-hewan').prop('disabled', true);
                $('#idhewan').val('');
                $('#hewan_nama').val('');
                $('#hewan-info').text('Pelanggan umum tidak memiliki data hewan');
            } else {
                $('#btn-pilih-pelanggan').prop('disabled', false);
                $('#hewan-info').text('Pilih pelanggan terlebih dahulu');
            }
        });

        // Handle pelanggan selection
        $(document).on('click', '.btn-pilih-pelanggan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#idpelanggan').val(id);
            $('#pelanggan_nama').val(nama);
            $('#pelanggan_umum').prop('checked', false);
            $('#modalPilihPelanggan').modal('hide');

            // Enable hewan selection and load hewan data
            $('#btn-pilih-hewan').prop('disabled', false);
            $('#hewan-info').text('');

            // Load hewan data for this pelanggan
            $.ajax({
                url: '<?= site_url('admin/perawatan/getHewanByPelanggan/') ?>' + id,
                type: 'GET',
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        let html = '';
                        if (response.data.length > 0) {
                            $.each(response.data, function(i, item) {
                                html += `<tr>
                                    <td>${item.idhewan}</td>
                                    <td>${item.namahewan}</td>
                                    <td>${item.jenis}</td>
                                    <td>${item.umur}</td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-hewan"
                                            data-id="${item.idhewan}"
                                            data-nama="${item.namahewan}">
                                            Pilih
                                        </button>
                                    </td>
                                </tr>`;
                            });
                        } else {
                            html = '<tr><td colspan="5" class="text-center">Tidak ada data hewan</td></tr>';
                        }
                        $('#tableHewan tbody').html(html);
                    }
                }
            });
        });

        // Reset pelanggan button
        $('#btn-reset-pelanggan').click(function() {
            $('#idpelanggan').val('');
            $('#pelanggan_nama').val('');
            $('#pelanggan_umum').prop('checked', false);
            $('#idhewan').val('');
            $('#hewan_nama').val('');
            $('#btn-pilih-hewan').prop('disabled', true);
            $('#hewan-info').text('Pilih pelanggan terlebih dahulu');
        });

        // Open pelanggan modal
        $('#btn-pilih-pelanggan').click(function() {
            $('#modalPilihPelanggan').modal('show');
        });

        // Handle hewan selection
        $(document).on('click', '.btn-pilih-hewan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#idhewan').val(id);
            $('#hewan_nama').val(nama);
            $('#modalPilihHewan').modal('hide');
        });

        // Reset hewan button
        $('#btn-reset-hewan').click(function() {
            $('#idhewan').val('');
            $('#hewan_nama').val('');
        });

        // Open hewan modal
        $('#btn-pilih-hewan').click(function() {
            $('#modalPilihHewan').modal('show');
        });

        // Open fasilitas modal
        $('#btn-tambah-fasilitas').click(function() {
            $('#modalPilihFasilitas').modal('show');
        });

        // Handle fasilitas selection
        $(document).on('click', '.btn-pilih-fasilitas', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');
            const harga = $(this).data('harga');
            const satuan = $(this).data('satuan');

            // Add to table
            const rowCount = $('#table-fasilitas tbody tr').length + 1;
            const newRow = `<tr>
                <td>${rowCount}</td>
                <td>
                    ${nama}
                    <input type="hidden" name="detailkdfasilitas[]" value="${id}">
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm harga" name="harga[]" value="${harga}" readonly>
                </td>
                <td>${satuan}</td>
                <td>
                    <input type="number" class="form-control form-control-sm jumlah" name="jumlah[]" value="1" min="1" required>
                </td>
                <td>
                    <input type="number" class="form-control form-control-sm totalharga" name="totalharga[]" value="${harga}" readonly>
                </td>
                <td>
                    <button type="button" class="btn btn-sm btn-danger btn-hapus-fasilitas">
                        <i class="fas fa-trash"></i>
                    </button>
                </td>
            </tr>`;

            $('#table-fasilitas tbody').append(newRow);
            $('#modalPilihFasilitas').modal('hide');
            calculateTotal();
        });
    });
</script>
<?= $this->endSection() ?>