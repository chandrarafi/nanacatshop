<?php

namespace App\Controllers;

use App\Models\BarangMasukModel;
use App\Models\DetailBarangMasukModel;
use App\Models\BarangModel;
use App\Models\SupplierModel;
use Hermawan\DataTables\DataTable;

class BarangMasukController extends BaseController
{
    protected $barangMasukModel;
    protected $detailBarangMasukModel;
    protected $barangModel;
    protected $supplierModel;
    protected $db;

    public function __construct()
    {
        $this->barangMasukModel = new BarangMasukModel();
        $this->detailBarangMasukModel = new DetailBarangMasukModel();
        $this->barangModel = new BarangModel();
        $this->supplierModel = new SupplierModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Barang Masuk';
        $supplier = $this->supplierModel->findAll();
        return view('admin/barangmasuk/index', compact('title', 'supplier'));
    }

    public function create()
    {
        $title = 'Tambah Barang Masuk';
        $supplier = $this->supplierModel->findAll();
        $barang = $this->barangModel->findAll();
        return view('admin/barangmasuk/create', compact('title', 'supplier', 'barang'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Barang Masuk';
        $barangMasuk = $this->barangMasukModel->find($id);

        if (!$barangMasuk) {
            return redirect()->to('admin/barangmasuk')->with('error', 'Data barang masuk tidak ditemukan');
        }

        $detailBarangMasuk = $this->detailBarangMasukModel->getDetailWithBarang($id);
        $supplier = $this->supplierModel->findAll();
        $barang = $this->barangModel->findAll();

        return view('admin/barangmasuk/edit', compact('title', 'barangMasuk', 'detailBarangMasuk', 'supplier', 'barang'));
    }

    public function getBarangMasuk()
    {
        $supplierFilter = $this->request->getGet('kdspl') ?? '';
        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? '';
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? '';

        $builder = $this->barangMasukModel->builder();
        $builder->select('barangmasuk.kdmasuk, barangmasuk.tglmasuk, barangmasuk.grandtotal, barangmasuk.status, supplier.namaspl');
        $builder->join('supplier', 'supplier.kdspl = barangmasuk.kdspl', 'left');

        if (!empty($supplierFilter)) {
            $builder->where('barangmasuk.kdspl', $supplierFilter);
        }

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $builder->where('barangmasuk.tglmasuk >=', $tanggalMulai);
            $builder->where('barangmasuk.tglmasuk <=', $tanggalAkhir);
        }

        return DataTable::of($builder)
            ->format('tglmasuk', function ($value) {
                return date('d/m/Y', strtotime($value));
            })
            ->format('grandtotal', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('status', function ($value) {
                if ($value == 1) {
                    return '<span class="badge bg-success">Selesai</span>';
                } else {
                    return '<span class="badge bg-warning">Pending</span>';
                }
            })
            ->add('action', function ($row) {
                return '<div class="d-flex gap-1">
                    <a href="' . site_url('admin/barangmasuk/edit/' . $row->kdmasuk) . '" class="btn btn-sm btn-info">
                        <i class="bi bi-pencil-square"></i>
                    </a>
                    <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdmasuk . '">
                        <i class="bi bi-trash"></i>
                    </button>
                    <a href="' . site_url('admin/barangmasuk/detail/' . $row->kdmasuk) . '" class="btn btn-sm btn-primary">
                        <i class="bi bi-eye"></i>
                    </a>
                </div>';
            })
            ->toJson(true);
    }

    public function getNextKdMasuk()
    {
        $nextId = $this->barangMasukModel->generateKdMasuk();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdmasuk' => $nextId]
        ]);
    }

    public function getBarangMasukById($id = null)
    {
        $data = $this->barangMasukModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data barang masuk tidak ditemukan'
        ]);
    }

    public function getDetailBarangMasuk($kdmasuk)
    {
        $data = $this->detailBarangMasukModel->getDetailWithBarang($kdmasuk);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => []
        ]);
    }

    public function getBarangById($id = null)
    {
        $data = $this->barangModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data barang tidak ditemukan'
        ]);
    }

    public function addBarangMasuk()
    {
        $this->db->transStart();

        try {
            $data = [
                'kdmasuk' => $this->barangMasukModel->generateKdMasuk(),
                'tglmasuk' => $this->request->getPost('tglmasuk'),
                'kdspl' => $this->request->getPost('kdspl'),
                'grandtotal' => $this->request->getPost('grandtotal'),
                'keterangan' => $this->request->getPost('keterangan') ?? '',
                'status' => $this->request->getPost('status') ?? 0,
            ];

            // Simpan header barang masuk
            $this->barangMasukModel->save($data);

            // Simpan detail barang masuk
            $detailKdBarang = $this->request->getPost('detailkdbarang');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');
            $detailNamaBarang = $this->request->getPost('namabarang');

            if (is_array($detailKdBarang) && count($detailKdBarang) > 0) {
                for ($i = 0; $i < count($detailKdBarang); $i++) {
                    $detailData = [
                        'detailkdmasuk' => $data['kdmasuk'],
                        'detailkdbarang' => $detailKdBarang[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                        'namabarang' => $detailNamaBarang[$i],
                    ];

                    $this->detailBarangMasukModel->save($detailData);

                    // Update stok barang jika status = 1 (selesai)
                    if ($data['status'] == 1) {
                        $barang = $this->barangModel->find($detailKdBarang[$i]);
                        if ($barang) {
                            $stokBaru = $barang['jumlah'] + $detailJumlah[$i];
                            $this->barangModel->update($detailKdBarang[$i], ['jumlah' => $stokBaru]);
                        }
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menyimpan data');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang masuk berhasil ditambahkan',
                'data' => ['kdmasuk' => $data['kdmasuk']]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menambahkan data barang masuk: ' . $e->getMessage()
            ]);
        }
    }

    public function updateBarangMasuk($id = null)
    {
        $this->db->transStart();

        try {
            // Cek apakah data exists
            $existingBarangMasuk = $this->barangMasukModel->find($id);
            if (!$existingBarangMasuk) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data barang masuk tidak ditemukan'
                ]);
            }

            $data = [
                'kdmasuk' => $id,
                'tglmasuk' => $this->request->getPost('tglmasuk'),
                'kdspl' => $this->request->getPost('kdspl'),
                'grandtotal' => $this->request->getPost('grandtotal'),
                'keterangan' => $this->request->getPost('keterangan') ?? '',
                'status' => $this->request->getPost('status') ?? 0,
            ];

            // Update header barang masuk
            $this->barangMasukModel->save($data);

            // Ambil status lama untuk menentukan apakah perlu rollback stok
            $statusLama = $existingBarangMasuk['status'];
            $statusBaru = $data['status'];

            // Hapus semua detail lama dan rollback stok jika status sebelumnya = 1
            if ($statusLama == 1) {
                // Ambil detail lama untuk rollback stok
                $detailLama = $this->detailBarangMasukModel->getDetailByKdMasuk($id);

                foreach ($detailLama as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Rollback stok (kurangi)
                        $stokBaru = $barang['jumlah'] - $item['jumlah'];
                        $stokBaru = max(0, $stokBaru); // Pastikan stok tidak negatif
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Hapus semua detail lama
            $this->detailBarangMasukModel->where('detailkdmasuk', $id)->delete();

            // Simpan detail barang masuk baru
            $detailKdBarang = $this->request->getPost('detailkdbarang');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');
            $detailNamaBarang = $this->request->getPost('namabarang');

            if (is_array($detailKdBarang) && count($detailKdBarang) > 0) {
                for ($i = 0; $i < count($detailKdBarang); $i++) {
                    $detailData = [
                        'detailkdmasuk' => $id,
                        'detailkdbarang' => $detailKdBarang[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                        'namabarang' => $detailNamaBarang[$i],
                    ];

                    $this->detailBarangMasukModel->save($detailData);

                    // Update stok barang jika status baru = 1 (selesai)
                    if ($statusBaru == 1) {
                        $barang = $this->barangModel->find($detailKdBarang[$i]);
                        if ($barang) {
                            $stokBaru = $barang['jumlah'] + $detailJumlah[$i];
                            $this->barangModel->update($detailKdBarang[$i], ['jumlah' => $stokBaru]);
                        }
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat memperbarui data');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang masuk berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui data barang masuk: ' . $e->getMessage()
            ]);
        }
    }

    public function deleteBarangMasuk($id = null)
    {
        $this->db->transStart();

        try {
            // Cek apakah data exists
            $existingBarangMasuk = $this->barangMasukModel->find($id);
            if (!$existingBarangMasuk) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data barang masuk tidak ditemukan'
                ]);
            }

            // Jika status = 1 (selesai), rollback stok
            if ($existingBarangMasuk['status'] == 1) {
                $detailBarangMasuk = $this->detailBarangMasukModel->getDetailByKdMasuk($id);

                foreach ($detailBarangMasuk as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Rollback stok (kurangi)
                        $stokBaru = $barang['jumlah'] - $item['jumlah'];
                        $stokBaru = max(0, $stokBaru); // Pastikan stok tidak negatif
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Hapus detail
            $this->detailBarangMasukModel->where('detailkdmasuk', $id)->delete();

            // Hapus header
            $this->barangMasukModel->delete($id);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menghapus data');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data barang masuk berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data barang masuk: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($id = null)
    {
        $title = 'Detail Barang Masuk';
        $barangMasuk = $this->barangMasukModel->getWithSupplier($id);

        if (!$barangMasuk) {
            return redirect()->to('admin/barangmasuk')->with('error', 'Data barang masuk tidak ditemukan');
        }

        $detailBarangMasuk = $this->detailBarangMasukModel->getDetailWithBarang($id);

        return view('admin/barangmasuk/detail', compact('title', 'barangMasuk', 'detailBarangMasuk'));
    }

    // Function to handle change status 
    public function changeStatus($id)
    {
        $this->db->transStart();

        try {
            $barangMasuk = $this->barangMasukModel->find($id);

            if (!$barangMasuk) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data barang masuk tidak ditemukan'
                ]);
            }

            $newStatus = $barangMasuk['status'] == 1 ? 0 : 1;

            // Jika status diubah menjadi 1 (selesai), update stok
            if ($newStatus == 1) {
                $detailBarangMasuk = $this->detailBarangMasukModel->getDetailByKdMasuk($id);

                foreach ($detailBarangMasuk as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Tambah stok
                        $stokBaru = $barang['jumlah'] + $item['jumlah'];
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }
            // Jika status diubah menjadi 0 (pending), rollback stok
            else {
                $detailBarangMasuk = $this->detailBarangMasukModel->getDetailByKdMasuk($id);

                foreach ($detailBarangMasuk as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Kurangi stok
                        $stokBaru = $barang['jumlah'] - $item['jumlah'];
                        $stokBaru = max(0, $stokBaru); // Pastikan stok tidak negatif
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Update status
            $this->barangMasukModel->update($id, ['status' => $newStatus]);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat mengubah status');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status barang masuk berhasil diubah',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status barang masuk: ' . $e->getMessage()
            ]);
        }
    }
}
