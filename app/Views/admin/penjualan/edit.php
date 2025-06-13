<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="container-fluid px-4">
    <h1 class="mt-4"><?= $title ?></h1>
    <ol class="breadcrumb mb-4">
        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
        <li class="breadcrumb-item"><a href="<?= site_url('admin/penjualan') ?>">Penjualan</a></li>
        <li class="breadcrumb-item active"><?= $title ?></li>
    </ol>
    <div class="card mb-4">
        <div class="card-header">
            <i class="fas fa-edit me-1"></i>
            Form Edit Penjualan
        </div>
        <div class="card-body">
            <form id="form-penjualan">
                <input type="hidden" name="id" value="<?= $penjualan['kdpenjualan'] ?>">
                <div class="row mb-3">
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="kdpenjualan" class="form-label">Kode Penjualan</label>
                            <input type="text" class="form-control" id="kdpenjualan" name="kdpenjualan" readonly value="<?= $penjualan['kdpenjualan'] ?>">
                        </div>
                        <div class="form-group mb-3">
                            <label for="tglpenjualan" class="form-label">Tanggal Penjualan</label>
                            <input type="date" class="form-control" id="tglpenjualan" name="tglpenjualan" required value="<?= $penjualan['tglpenjualan'] ?>">
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="form-group mb-3">
                            <label for="idpelanggan" class="form-label">Pelanggan</label>
                            <div class="input-group">
                                <?php
                                // Ambil nama pelanggan dari data yang tersedia
                                $namaPelanggan = $penjualan['idpelanggan'] ? '' : 'Pelanggan Umum';
                                foreach ($pelanggan as $p) {
                                    if ($p['idpelanggan'] == $penjualan['idpelanggan']) {
                                        $namaPelanggan = $p['nama'];
                                        break;
                                    }
                                }
                                ?>
                                <input type="text" class="form-control" id="namapelanggan" placeholder="Pilih Pelanggan" readonly value="<?= $namaPelanggan ?>">
                                <input type="hidden" id="idpelanggan" name="idpelanggan" value="<?= $penjualan['idpelanggan'] ?>">
                                <button class="btn btn-outline-primary" type="button" id="btn-pilih-pelanggan">
                                    <i class="bi bi-search"></i>
                                </button>
                                <button class="btn btn-outline-secondary" type="button" id="btn-pelanggan-umum">
                                    <i class="bi bi-people"></i> Umum
                                </button>
                            </div>
                            <small class="text-muted">*Pilih "Umum" untuk pelanggan yang tidak terdata</small>
                        </div>
                        <div class="form-group mb-3">
                            <label for="status" class="form-label">Status</label>
                            <select class="form-select" id="status" name="status">
                                <option value="0" <?= $penjualan['status'] == 0 ? 'selected' : '' ?>>Pending</option>
                                <option value="1" <?= $penjualan['status'] == 1 ? 'selected' : '' ?>>Selesai</option>
                            </select>
                            <small class="text-muted">*Status 'Selesai' akan langsung mengurangi stok barang</small>
                        </div>
                    </div>
                </div>

                <div class="divider">
                    <div class="divider-text">Detail Barang</div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-12">
                        <button type="button" class="btn btn-primary btn-sm" id="btn-tambah-barang">
                            <i class="bi bi-plus-circle"></i> Tambah Barang
                        </button>
                    </div>
                </div>

                <div class="table-responsive mb-3">
                    <table class="table table-bordered" id="table-detail">
                        <thead>
                            <tr>
                                <th width="5%">No</th>
                                <th width="25%">Barang</th>
                                <th width="15%">Jumlah</th>
                                <th width="20%">Harga</th>
                                <th width="20%">Total</th>
                                <th width="15%">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (empty($detailPenjualan)) : ?>
                                <tr id="row-empty">
                                    <td colspan="6" class="text-center">Belum ada data barang</td>
                                </tr>
                            <?php else : ?>
                                <?php $i = 1;
                                foreach ($detailPenjualan as $detail) : ?>
                                    <tr id="row-<?= $detail['detailkdbarang'] ?>">
                                        <td><?= $i++ ?></td>
                                        <td>
                                            <?= $detail['namabarang'] ?>
                                            <input type="hidden" name="detailkdbarang[]" value="<?= $detail['detailkdbarang'] ?>">
                                            <input type="hidden" name="namabarang[]" value="<?= $detail['namabarang'] ?>">
                                        </td>
                                        <td>
                                            <input type="number" class="form-control jumlah" name="jumlah[]" value="<?= $detail['jumlah'] ?>" min="1" required>
                                        </td>
                                        <td>
                                            <input type="number" class="form-control harga" name="harga[]" value="<?= $detail['harga'] ?>" min="0" required>
                                        </td>
                                        <td>
                                            <span class="total-harga">Rp <?= number_format($detail['totalharga'], 0, ',', '.') ?></span>
                                            <input type="hidden" class="total-input" name="totalharga[]" value="<?= $detail['totalharga'] ?>">
                                        </td>
                                        <td>
                                            <button type="button" class="btn btn-sm btn-danger btn-hapus-barang" data-id="<?= $detail['detailkdbarang'] ?>">
                                                <i class="bi bi-trash"></i>
                                            </button>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                        <tfoot>
                            <tr>
                                <th colspan="4" class="text-end">Grand Total</th>
                                <th id="grand-total">Rp <?= number_format($penjualan['grandtotal'], 0, ',', '.') ?></th>
                                <th></th>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <div class="d-flex justify-content-between">
                    <a href="<?= site_url('admin/penjualan') ?>" class="btn btn-secondary">Batal</a>
                    <button type="submit" class="btn btn-primary" id="btn-simpan">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Pilih Pelanggan -->
<div class="modal fade" id="modalPelanggan" tabindex="-1" aria-labelledby="modalPelangganLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPelangganLabel">Pilih Pelanggan</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table-pelanggan">
                        <thead>
                            <tr>
                                <th>ID Pelanggan</th>
                                <th>Nama</th>
                                <th>No. HP</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($pelanggan as $p) : ?>
                                <tr>
                                    <td><?= $p['idpelanggan'] ?></td>
                                    <td><?= $p['nama'] ?></td>
                                    <td><?= $p['nohp'] ?></td>
                                    <td><?= $p['alamat'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-pelanggan"
                                            data-id="<?= $p['idpelanggan'] ?>"
                                            data-nama="<?= $p['nama'] ?>">
                                            <i class="bi bi-check-circle"></i> Pilih
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalBarang" tabindex="-1" aria-labelledby="modalBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalBarangLabel">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-bordered table-striped" id="table-barang">
                        <thead>
                            <tr>
                                <th>Kode Barang</th>
                                <th>Nama Barang</th>
                                <th>Harga Jual</th>
                                <th>Stok</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($barang as $b) : ?>
                                <tr class="<?= $b['jumlah'] <= 0 ? 'table-danger' : '' ?>">
                                    <td><?= $b['kdbarang'] ?></td>
                                    <td><?= $b['namabarang'] ?></td>
                                    <td><?= number_format($b['hargajual'], 0, ',', '.') ?></td>
                                    <td><?= $b['jumlah'] ?></td>
                                    <td>
                                        <?php if ($b['jumlah'] > 0) : ?>
                                            <button type="button" class="btn btn-sm btn-primary btn-pilih-barang"
                                                data-id="<?= $b['kdbarang'] ?>"
                                                data-nama="<?= $b['namabarang'] ?>"
                                                data-harga="<?= $b['hargajual'] ?>"
                                                data-stok="<?= $b['jumlah'] ?>">
                                                <i class="bi bi-check-circle"></i> Pilih
                                            </button>
                                        <?php else : ?>
                                            <button type="button" class="btn btn-sm btn-secondary" disabled>
                                                <i class="bi bi-x-circle"></i> Stok Habis
                                            </button>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
            </div>
        </div>
    </div>
</div>

<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        let tableBarang = $('#table-barang').DataTable();
        let tablePelanggan = $('#table-pelanggan').DataTable();
        let oldStatus = <?= $penjualan['status'] ?>;

        // Perbaiki penomoran pada load awal
        renumberRows();

        // Button Pilih Pelanggan Click
        $('#btn-pilih-pelanggan').click(function() {
            $('#modalPelanggan').modal('show');
        });

        // Button Pelanggan Umum Click
        $('#btn-pelanggan-umum').click(function() {
            $('#idpelanggan').val('');
            $('#namapelanggan').val('Pelanggan Umum');
        });

        // Button Pilih Pelanggan dari Modal
        $(document).on('click', '.btn-pilih-pelanggan', function() {
            const id = $(this).data('id');
            const nama = $(this).data('nama');

            $('#idpelanggan').val(id);
            $('#namapelanggan').val(nama);
            $('#modalPelanggan').modal('hide');
        });

        // Button Tambah Barang
        $('#btn-tambah-barang').click(function() {
            $('#modalBarang').modal('show');
        });

        // Button Pilih Barang dari Modal
        $(document).on('click', '.btn-pilih-barang', function() {
            const kdbarang = $(this).data('id');
            const namabarang = $(this).data('nama');
            const harga = $(this).data('harga');
            const stok = $(this).data('stok');

            // Cek apakah barang sudah ada di tabel
            if ($(`#row-${kdbarang}`).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Barang ini sudah ditambahkan ke dalam list',
                });
                return;
            }

            // Cek apakah stok tersedia
            if (stok <= 0) {
                Swal.fire({
                    icon: 'error',
                    title: 'Stok Tidak Tersedia',
                    text: 'Stok barang ini kosong, tidak dapat ditambahkan ke keranjang',
                });
                return;
            }

            // Sembunyikan row kosong
            $('#row-empty').hide();

            // Tambah row
            let rowCount = $('#table-detail tbody tr').not('#row-empty').length;

            let newRow = `
                <tr id="row-${kdbarang}">
                    <td>${rowCount + 1}</td>
                    <td>
                        ${namabarang}
                        <input type="hidden" name="detailkdbarang[]" value="${kdbarang}">
                        <input type="hidden" name="namabarang[]" value="${namabarang}">
                    </td>
                    <td>
                        <input type="number" class="form-control jumlah" name="jumlah[]" value="1" min="1" max="${stok}" required>
                        <small class="text-muted">Stok: ${stok}</small>
                    </td>
                    <td>
                        <input type="number" class="form-control harga" name="harga[]" value="${harga}" min="0" required>
                    </td>
                    <td>
                        <span class="total-harga">Rp ${formatRupiah(harga)}</span>
                        <input type="hidden" class="total-input" name="totalharga[]" value="${harga}">
                    </td>
                    <td>
                        <button type="button" class="btn btn-sm btn-danger btn-hapus-barang" data-id="${kdbarang}">
                            <i class="bi bi-trash"></i>
                        </button>
                    </td>
                </tr>
            `;
            $('#table-detail tbody').append(newRow);
            hitungGrandTotal();
            $('#modalBarang').modal('hide');

            // Reorder numbers setelah menambah barang
            renumberRows();
        });

        // Hapus barang
        $(document).on('click', '.btn-hapus-barang', function() {
            const kdbarang = $(this).data('id');
            $(`#row-${kdbarang}`).remove();

            // Show empty row if no items
            if ($('#table-detail tbody tr').not('#row-empty').length === 0) {
                $('#row-empty').show();
            } else {
                // Reorder numbers
                renumberRows();
            }

            hitungGrandTotal();
        });

        // Fungsi untuk mengurutkan ulang nomor
        function renumberRows() {
            let i = 1;
            $('#table-detail tbody tr').not('#row-empty').each(function() {
                $(this).find('td:first').text(i);
                i++;
            });
        }

        // Hitung total saat jumlah atau harga berubah
        $(document).on('change', '.jumlah, .harga', function() {
            const row = $(this).closest('tr');
            const jumlah = parseInt(row.find('.jumlah').val()) || 0;
            const harga = parseFloat(row.find('.harga').val()) || 0;
            const total = jumlah * harga;

            // Validasi jumlah tidak melebihi stok
            if ($(this).hasClass('jumlah')) {
                const maxStok = parseInt($(this).attr('max'));
                if (jumlah > maxStok) {
                    Swal.fire({
                        icon: 'warning',
                        title: 'Perhatian',
                        text: `Jumlah melebihi stok yang tersedia (${maxStok})`
                    });
                    $(this).val(maxStok);
                    return;
                }
            }

            row.find('.total-harga').text('Rp ' + formatRupiah(total));
            row.find('.total-input').val(total);

            hitungGrandTotal();
        });

        // Hitung grand total
        function hitungGrandTotal() {
            let grandTotal = 0;
            $('.total-input').each(function() {
                grandTotal += parseFloat($(this).val()) || 0;
            });

            $('#grand-total').text('Rp ' + formatRupiah(grandTotal));
            $('input[name="grandtotal"]').remove();
            $('#form-penjualan').append(`<input type="hidden" name="grandtotal" value="${grandTotal}">`);
        }

        // Format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Form Submit
        $('#form-penjualan').submit(function(e) {
            e.preventDefault();

            // Validasi
            if ($('#table-detail tbody tr').not('#row-empty').length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap tambahkan minimal 1 barang'
                });
                return;
            }

            // Validasi jumlah tidak melebihi stok
            let isValid = true;
            $('.jumlah').each(function() {
                const jumlah = parseInt($(this).val()) || 0;
                const maxStok = parseInt($(this).attr('max'));
                if (jumlah > maxStok) {
                    const namaBarang = $(this).closest('tr').find('td:eq(1)').text().trim();
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: `Jumlah ${namaBarang} melebihi stok yang tersedia (${maxStok})`
                    });
                    isValid = false;
                    return false;
                }
            });

            if (!isValid) return;

            // Konfirmasi jika mengubah status dari pending ke selesai
            const currentStatus = $('#status').val();
            if (oldStatus == 0 && currentStatus == 1) {
                Swal.fire({
                    title: 'Konfirmasi',
                    text: 'Mengubah status menjadi Selesai akan mengurangi stok barang. Lanjutkan?',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Ya, Lanjutkan',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        submitForm();
                    }
                });
            } else {
                submitForm();
            }
        });

        // Fungsi untuk submit form
        function submitForm() {
            $.ajax({
                url: '<?= site_url('admin/penjualan/update/' . $penjualan['kdpenjualan']) ?>',
                type: 'POST',
                data: $('#form-penjualan').serialize(),
                dataType: 'json',
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Sukses',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= site_url('admin/penjualan') ?>';
                        });
                    } else {
                        Swal.fire({
                            icon: 'error',
                            title: 'Error',
                            text: response.message
                        });
                    }
                },
                error: function(xhr, status, error) {
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Error',
                        text: response?.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        }

        // Inisialisasi perhitungan grand total saat halaman dimuat
        hitungGrandTotal();

        // Tambahkan informasi stok pada item yang sudah ada
        $('#table-detail tbody tr').not('#row-empty').each(function() {
            const jumlahInput = $(this).find('.jumlah');
            const maxStok = jumlahInput.attr('max');
            if (maxStok) {
                jumlahInput.after(`<small class="text-muted">Stok: ${maxStok}</small>`);
            }
        });
    });
</script>
<?= $this->endSection() ?>