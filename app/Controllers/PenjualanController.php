<?php

namespace App\Controllers;

use App\Models\PenjualanModel;
use App\Models\DetailPenjualanModel;
use App\Models\BarangModel;
use App\Models\PelangganModel;
use Hermawan\DataTables\DataTable;

class PenjualanController extends BaseController
{
    protected $penjualanModel;
    protected $detailPenjualanModel;
    protected $barangModel;
    protected $pelangganModel;
    protected $db;

    public function __construct()
    {
        $this->penjualanModel = new PenjualanModel();
        $this->detailPenjualanModel = new DetailPenjualanModel();
        $this->barangModel = new BarangModel();
        $this->pelangganModel = new PelangganModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Penjualan';
        $pelanggan = $this->pelangganModel->findAll();
        return view('admin/penjualan/index', compact('title', 'pelanggan'));
    }

    public function create()
    {
        $title = 'Tambah Penjualan';
        $pelanggan = $this->pelangganModel->findAll();
        $barang = $this->barangModel->findAll();
        return view('admin/penjualan/create', compact('title', 'pelanggan', 'barang'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Penjualan';
        $penjualan = $this->penjualanModel->find($id);

        if (!$penjualan) {
            return redirect()->to('admin/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $detailPenjualan = $this->detailPenjualanModel->getDetailWithBarang($id);
        $pelanggan = $this->pelangganModel->findAll();
        $barang = $this->barangModel->findAll();

        return view('admin/penjualan/edit', compact('title', 'penjualan', 'detailPenjualan', 'pelanggan', 'barang'));
    }

    public function getPenjualan()
    {
        $pelangganFilter = $this->request->getGet('idpelanggan') ?? '';
        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? '';
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? '';

        $builder = $this->db->table('penjualan');
        $builder->select('penjualan.kdpenjualan, penjualan.tglpenjualan, penjualan.grandtotal, penjualan.status, penjualan.idpelanggan');
        $builder->select('IFNULL(pelanggan.nama, "Pelanggan Umum") as nama', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');
        $builder->orderBy('penjualan.kdpenjualan', 'DESC');

        if (!empty($pelangganFilter)) {
            $builder->where('penjualan.idpelanggan', $pelangganFilter);
        }

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $builder->where('penjualan.tglpenjualan >=', $tanggalMulai);
            $builder->where('penjualan.tglpenjualan <=', $tanggalAkhir);
        }

        // Gunakan DataTable dengan konfigurasi pencarian manual
        $dt = new DataTable($builder);

        // Nonaktifkan pencarian global otomatis
        $dt->setSearchableColumns(['penjualan.kdpenjualan']);

        // Format data
        $dt->format('tglpenjualan', function ($value) {
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
            });

        // Tambahkan kolom aksi
        $dt->add('action', function ($row) {
            return '<div class="d-flex gap-1">
                <a href="' . site_url('admin/penjualan/edit/' . $row->kdpenjualan) . '" class="btn btn-sm btn-info">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdpenjualan . '">
                    <i class="bi bi-trash"></i>
                </button>
                <a href="' . site_url('admin/penjualan/detail/' . $row->kdpenjualan) . '" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="' . site_url('admin/penjualan/cetak/' . $row->kdpenjualan) . '" class="btn btn-sm btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
            </div>';
        });

        return $dt->toJson(true);
    }

    public function getNextKdPenjualan()
    {
        $nextId = $this->penjualanModel->generateKdPenjualan();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdpenjualan' => $nextId]
        ]);
    }

    public function getPenjualanById($id = null)
    {
        $data = $this->penjualanModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data penjualan tidak ditemukan'
        ]);
    }

    public function getDetailPenjualan($kdpenjualan)
    {
        $data = $this->detailPenjualanModel->getDetailWithBarang($kdpenjualan);

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

    public function addPenjualan()
    {
        $this->db->transStart();

        try {
            $data = [
                'kdpenjualan' => $this->penjualanModel->generateKdPenjualan(),
                'tglpenjualan' => $this->request->getPost('tglpenjualan'),
                'idpelanggan' => $this->request->getPost('idpelanggan') ?: null,
                'grandtotal' => $this->request->getPost('grandtotal'),
                'status' => $this->request->getPost('status') ?? 0,
            ];

            // Simpan header penjualan
            $this->penjualanModel->save($data);

            // Simpan detail penjualan
            $detailKdBarang = $this->request->getPost('detailkdbarang');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');

            if (is_array($detailKdBarang) && count($detailKdBarang) > 0) {
                for ($i = 0; $i < count($detailKdBarang); $i++) {
                    // Cek stok barang
                    $barang = $this->barangModel->find($detailKdBarang[$i]);
                    if (!$barang || ($data['status'] == 1 && $barang['jumlah'] < $detailJumlah[$i])) {
                        throw new \Exception('Stok barang tidak mencukupi untuk ' . ($barang ? $barang['namabarang'] : 'barang yang dipilih'));
                    }

                    $detailData = [
                        'detailkdpenjualan' => $data['kdpenjualan'],
                        'detailkdbarang' => $detailKdBarang[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                    ];

                    $this->detailPenjualanModel->save($detailData);

                    // Update stok barang jika status = 1 (selesai)
                    if ($data['status'] == 1) {
                        if ($barang) {
                            $stokBaru = $barang['jumlah'] - $detailJumlah[$i];
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
                'message' => 'Data penjualan berhasil ditambahkan',
                'data' => [
                    'kdpenjualan' => $data['kdpenjualan'],
                    'print_url' => site_url('admin/penjualan/cetak/' . $data['kdpenjualan'])
                ]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menambahkan data penjualan: ' . $e->getMessage()
            ]);
        }
    }

    public function updatePenjualan($id = null)
    {
        $this->db->transStart();

        try {
            // Cek apakah data exists
            $existingPenjualan = $this->penjualanModel->find($id);
            if (!$existingPenjualan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }

            $data = [
                'kdpenjualan' => $id,
                'tglpenjualan' => $this->request->getPost('tglpenjualan'),
                'idpelanggan' => $this->request->getPost('idpelanggan') ?: null,
                'grandtotal' => $this->request->getPost('grandtotal'),
                'status' => $this->request->getPost('status') ?? 0,
            ];

            // Update header penjualan
            $this->penjualanModel->save($data);

            // Ambil status lama untuk menentukan apakah perlu rollback stok
            $statusLama = $existingPenjualan['status'];
            $statusBaru = $data['status'];

            // Hapus semua detail lama dan rollback stok jika status sebelumnya = 1
            if ($statusLama == 1) {
                // Ambil detail lama untuk rollback stok
                $detailLama = $this->detailPenjualanModel->getDetailByKdPenjualan($id);

                foreach ($detailLama as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Rollback stok (tambah kembali)
                        $stokBaru = $barang['jumlah'] + $item['jumlah'];
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Hapus semua detail lama
            $this->detailPenjualanModel->where('detailkdpenjualan', $id)->delete();

            // Simpan detail penjualan baru
            $detailKdBarang = $this->request->getPost('detailkdbarang');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');

            if (is_array($detailKdBarang) && count($detailKdBarang) > 0) {
                for ($i = 0; $i < count($detailKdBarang); $i++) {
                    // Cek stok barang jika status baru = 1
                    $barang = $this->barangModel->find($detailKdBarang[$i]);
                    if ($statusBaru == 1 && (!$barang || $barang['jumlah'] < $detailJumlah[$i])) {
                        throw new \Exception('Stok barang tidak mencukupi untuk ' . ($barang ? $barang['namabarang'] : 'barang yang dipilih'));
                    }

                    $detailData = [
                        'detailkdpenjualan' => $id,
                        'detailkdbarang' => $detailKdBarang[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                    ];

                    $this->detailPenjualanModel->save($detailData);

                    // Update stok barang jika status baru = 1 (selesai)
                    if ($statusBaru == 1) {
                        if ($barang) {
                            $stokBaru = $barang['jumlah'] - $detailJumlah[$i];
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
                'message' => 'Data penjualan berhasil diperbarui'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui data penjualan: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePenjualan($id = null)
    {
        $this->db->transStart();

        try {
            // Cek apakah data exists
            $existingPenjualan = $this->penjualanModel->find($id);
            if (!$existingPenjualan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }

            // Jika status = 1 (selesai), rollback stok
            if ($existingPenjualan['status'] == 1) {
                $detailPenjualan = $this->detailPenjualanModel->getDetailByKdPenjualan($id);

                foreach ($detailPenjualan as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Rollback stok (tambah kembali)
                        $stokBaru = $barang['jumlah'] + $item['jumlah'];
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Hapus detail
            $this->detailPenjualanModel->where('detailkdpenjualan', $id)->delete();

            // Hapus header
            $this->penjualanModel->delete($id);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menghapus data');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penjualan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data penjualan: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($id = null)
    {
        $title = 'Detail Penjualan';
        $penjualan = $this->penjualanModel->getWithPelanggan($id);

        if (!$penjualan) {
            return redirect()->to('admin/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $detailPenjualan = $this->detailPenjualanModel->getDetailWithBarang($id);

        return view('admin/penjualan/detail', compact('title', 'penjualan', 'detailPenjualan'));
    }

    // Function to handle change status 
    public function changeStatus($id)
    {
        $this->db->transStart();

        try {
            $penjualan = $this->penjualanModel->find($id);

            if (!$penjualan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data penjualan tidak ditemukan'
                ]);
            }

            $newStatus = $penjualan['status'] == 1 ? 0 : 1;

            // Jika status diubah menjadi 1 (selesai), update stok
            if ($newStatus == 1) {
                $detailPenjualan = $this->detailPenjualanModel->getDetailByKdPenjualan($id);

                foreach ($detailPenjualan as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Cek stok mencukupi
                        if ($barang['jumlah'] < $item['jumlah']) {
                            throw new \Exception('Stok barang tidak mencukupi untuk ' . $barang['namabarang']);
                        }

                        // Kurangi stok
                        $stokBaru = $barang['jumlah'] - $item['jumlah'];
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }
            // Jika status diubah menjadi 0 (pending), rollback stok
            else {
                $detailPenjualan = $this->detailPenjualanModel->getDetailByKdPenjualan($id);

                foreach ($detailPenjualan as $item) {
                    $barang = $this->barangModel->find($item['detailkdbarang']);
                    if ($barang) {
                        // Tambah stok (rollback)
                        $stokBaru = $barang['jumlah'] + $item['jumlah'];
                        $this->barangModel->update($item['detailkdbarang'], ['jumlah' => $stokBaru]);
                    }
                }
            }

            // Update status
            $this->penjualanModel->update($id, ['status' => $newStatus]);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat mengubah status');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status penjualan berhasil diubah',
                'new_status' => $newStatus
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status penjualan: ' . $e->getMessage()
            ]);
        }
    }

    public function cetak($id = null)
    {
        $penjualan = $this->penjualanModel->getWithPelanggan($id);

        if (!$penjualan) {
            return redirect()->to('admin/penjualan')->with('error', 'Data penjualan tidak ditemukan');
        }

        $detailPenjualan = $this->detailPenjualanModel->getDetailWithBarang($id);

        // Mencegah header dan footer dari view layout utama ditampilkan
        return view('admin/penjualan/cetak', compact('penjualan', 'detailPenjualan'));
    }
}
