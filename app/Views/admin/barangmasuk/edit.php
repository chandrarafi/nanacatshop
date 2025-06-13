<?= $this->extend('admin/layouts/main') ?>

<?= $this->section('content') ?>
<div class="page-heading">
    <div class="page-title">
        <div class="row">
            <div class="col-12 col-md-6 order-md-1 order-last">
                <h3>Edit Barang Masuk</h3>
                <p class="text-subtitle text-muted">Form edit data barang masuk</p>
            </div>
            <div class="col-12 col-md-6 order-md-2 order-first">
                <nav aria-label="breadcrumb" class="breadcrumb-header float-start float-lg-end">
                    <ol class="breadcrumb">
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/dashboard') ?>">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="<?= site_url('admin/barangmasuk') ?>">Barang Masuk</a></li>
                        <li class="breadcrumb-item active" aria-current="page">Edit</li>
                    </ol>
                </nav>
            </div>
        </div>
    </div>

    <section class="section">
        <div class="card">
            <div class="card-header">
                <h4 class="card-title">Form Edit Barang Masuk</h4>
            </div>
            <div class="card-body">
                <form id="form-barangmasuk">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kdmasuk">Kode Barang Masuk</label>
                                <input type="text" class="form-control" id="kdmasuk" name="kdmasuk" readonly value="<?= $barangMasuk['kdmasuk'] ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="tglmasuk">Tanggal Masuk</label>
                                <input type="date" class="form-control" id="tglmasuk" name="tglmasuk" required value="<?= date('Y-m-d', strtotime($barangMasuk['tglmasuk'])) ?>">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="kdspl">Supplier</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" id="namaspl" placeholder="Pilih Supplier" value="<?= isset($barangMasuk['namaspl']) ? $barangMasuk['namaspl'] : '' ?>" readonly>
                                    <input type="hidden" id="kdspl" name="kdspl" value="<?= $barangMasuk['kdspl'] ?>" required>
                                    <button class="btn btn-outline-primary" type="button" id="btn-pilih-supplier">
                                        <i class="bi bi-search"></i>
                                    </button>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-group">
                                <label for="status">Status</label>
                                <select class="form-select" id="status" name="status">
                                    <option value="0" <?= ($barangMasuk['status'] == 0) ? 'selected' : '' ?>>Pending</option>
                                    <option value="1" <?= ($barangMasuk['status'] == 1) ? 'selected' : '' ?>>Selesai</option>
                                </select>
                                <small class="text-muted">*Status 'Selesai' akan langsung menambahkan stok barang</small>
                            </div>
                        </div>
                        <div class="col-md-12">
                            <div class="form-group">
                                <label for="keterangan">Keterangan</label>
                                <textarea class="form-control" id="keterangan" name="keterangan" rows="3"><?= $barangMasuk['keterangan'] ?? '' ?></textarea>
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

                    <div class="table-responsive">
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
                                <?php if (empty($detailBarangMasuk)) : ?>
                                    <tr id="row-empty">
                                        <td colspan="6" class="text-center">Belum ada barang yang ditambahkan</td>
                                    </tr>
                                <?php else : ?>
                                    <?php foreach ($detailBarangMasuk as $index => $detail) : ?>
                                        <tr id="row-<?= $detail['detailkdbarang'] ?>">
                                            <td><?= $index + 1 ?></td>
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
                                    <th id="grand-total">Rp <?= number_format($barangMasuk['grandtotal'], 0, ',', '.') ?></th>
                                    <th></th>
                                </tr>
                            </tfoot>
                        </table>
                    </div>

                    <div class="row mt-4">
                        <div class="col-12 d-flex justify-content-end">
                            <a href="<?= site_url('admin/barangmasuk') ?>" class="btn btn-secondary me-2">Batal</a>
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
</div>

<!-- Modal Pilih Barang -->
<div class="modal fade" id="modalPilihBarang" tabindex="-1" role="dialog" aria-labelledby="modalPilihBarangLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihBarangLabel">Pilih Barang</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-pilih-barang">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Barang</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($barang as $b) : ?>
                                <tr>
                                    <td><?= $b['kdbarang'] ?></td>
                                    <td><?= $b['namabarang'] ?></td>
                                    <td><?= $b['jumlah'] ?></td>
                                    <td>Rp <?= number_format($b['hargabeli'], 0, ',', '.') ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-barang"
                                            data-id="<?= $b['kdbarang'] ?>"
                                            data-nama="<?= $b['namabarang'] ?>"
                                            data-harga="<?= $b['hargabeli'] ?>">
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

<!-- Modal Pilih Supplier -->
<div class="modal fade" id="modalPilihSupplier" tabindex="-1" role="dialog" aria-labelledby="modalPilihSupplierLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="modalPilihSupplierLabel">Pilih Supplier</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <div class="table-responsive">
                    <table class="table table-hover" id="table-pilih-supplier">
                        <thead>
                            <tr>
                                <th>Kode</th>
                                <th>Nama Supplier</th>
                                <th>Telepon</th>
                                <th>Alamat</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($supplier as $s) : ?>
                                <tr>
                                    <td><?= $s['kdspl'] ?></td>
                                    <td><?= $s['namaspl'] ?></td>
                                    <td><?= $s['telpspl'] ?></td>
                                    <td><?= $s['alamatspl'] ?></td>
                                    <td>
                                        <button type="button" class="btn btn-sm btn-primary btn-pilih-supplier"
                                            data-id="<?= $s['kdspl'] ?>"
                                            data-nama="<?= $s['namaspl'] ?>">
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
<?= $this->endSection() ?>

<?= $this->section('scripts') ?>
<script>
    $(document).ready(function() {
        // Initialize
        $('#table-pilih-barang').DataTable();
        $('#table-pilih-supplier').DataTable();
        hitungGrandTotal();

        // Pilih supplier button
        $('#btn-pilih-supplier').click(function() {
            $('#modalPilihSupplier').modal('show');
        });

        // Pilih supplier dari modal
        $('.btn-pilih-supplier').click(function() {
            const kdspl = $(this).data('id');
            const namaspl = $(this).data('nama');

            $('#kdspl').val(kdspl);
            $('#namaspl').val(namaspl);
            $('#modalPilihSupplier').modal('hide');
        });

        // Tambah barang button
        $('#btn-tambah-barang').click(function() {
            $('#modalPilihBarang').modal('show');
        });

        // Pilih barang
        $('.btn-pilih-barang').click(function() {
            const kdbarang = $(this).data('id');
            const namabarang = $(this).data('nama');
            const hargabeli = $(this).data('harga');

            // Check if barang already exists in table
            if ($(`#row-${kdbarang}`).length > 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Barang ini sudah ditambahkan ke dalam list',
                });
                return;
            }

            // Hide empty row
            $('#row-empty').hide();

            // Add row
            let rowCount = $('#table-detail tbody tr').length;
            if ($('#row-empty').is(':visible')) {
                rowCount = 0;
            }

            let newRow = `
                <tr id="row-${kdbarang}">
                    <td>${rowCount + 1}</td>
                    <td>
                        ${namabarang}
                        <input type="hidden" name="detailkdbarang[]" value="${kdbarang}">
                        <input type="hidden" name="namabarang[]" value="${namabarang}">
                    </td>
                    <td>
                        <input type="number" class="form-control jumlah" name="jumlah[]" value="1" min="1" required>
                    </td>
                    <td>
                        <input type="number" class="form-control harga" name="harga[]" value="${hargabeli}" min="0" required>
                    </td>
                    <td>
                        <span class="total-harga">Rp ${formatRupiah(hargabeli)}</span>
                        <input type="hidden" class="total-input" name="totalharga[]" value="${hargabeli}">
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
            $('#modalPilihBarang').modal('hide');
        });

        // Hapus barang
        $(document).on('click', '.btn-hapus-barang', function() {
            const kdbarang = $(this).data('id');
            $(`#row-${kdbarang}`).remove();

            // Reorder numbers
            let i = 1;
            $('#table-detail tbody tr').not('#row-empty').each(function() {
                $(this).find('td:first').text(i);
                i++;
            });

            // Show empty row if no items
            if ($('#table-detail tbody tr').not('#row-empty').length === 0) {
                $('#row-empty').show();
            }

            hitungGrandTotal();
        });

        // Hitung total saat jumlah atau harga berubah
        $(document).on('change', '.jumlah, .harga', function() {
            const row = $(this).closest('tr');
            const jumlah = parseInt(row.find('.jumlah').val()) || 0;
            const harga = parseFloat(row.find('.harga').val()) || 0;
            const total = jumlah * harga;

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
            $('#form-barangmasuk').append(`<input type="hidden" name="grandtotal" value="${grandTotal}">`);
        }

        // Format rupiah
        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID').format(angka);
        }

        // Form submit
        $('#form-barangmasuk').submit(function(e) {
            e.preventDefault();

            // Validasi
            if ($('#table-detail tbody tr').not('#row-empty').length === 0) {
                Swal.fire({
                    icon: 'warning',
                    title: 'Perhatian',
                    text: 'Harap tambahkan minimal 1 barang',
                });
                return;
            }

            $.ajax({
                url: '<?= site_url('admin/barangmasuk/updateBarangMasuk/' . $barangMasuk['kdmasuk']) ?>',
                method: 'POST',
                data: $(this).serialize(),
                success: function(response) {
                    if (response.status === 'success') {
                        Swal.fire({
                            icon: 'success',
                            title: 'Berhasil',
                            text: response.message,
                            showConfirmButton: false,
                            timer: 1500
                        }).then(() => {
                            window.location.href = '<?= site_url('admin/barangmasuk') ?>';
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
                    let response = xhr.responseJSON;
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: response?.message || 'Terjadi kesalahan saat menyimpan data'
                    });
                }
            });
        });
    });
</script>
<?= $this->endSection() ?>