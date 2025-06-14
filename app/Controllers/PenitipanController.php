<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use App\Models\PenitipanModel;
use App\Models\DetailPenitipanModel;
use App\Models\HewanModel;
use App\Models\PelangganModel;
use App\Models\FasilitasModel;
use Hermawan\DataTables\DataTable;

class PenitipanController extends BaseController
{
    protected $penitipanModel;
    protected $detailPenitipanModel;
    protected $hewanModel;
    protected $pelangganModel;
    protected $fasilitasModel;
    protected $db;

    public function __construct()
    {
        $this->penitipanModel = new PenitipanModel();
        $this->detailPenitipanModel = new DetailPenitipanModel();
        $this->hewanModel = new HewanModel();
        $this->pelangganModel = new PelangganModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Penitipan';
        $pelanggan = $this->pelangganModel->findAll();
        return view('admin/penitipan/index', compact('title', 'pelanggan'));
    }

    public function create()
    {
        $title = 'Tambah Penitipan';
        $pelanggan = $this->pelangganModel->findAll();
        $hewan = $this->hewanModel->findAll();
        $fasilitas = $this->fasilitasModel->getWithKategori();
        $kode_penitipan = $this->penitipanModel->generateKdPenitipan();
        return view('admin/penitipan/create', compact('title', 'pelanggan', 'hewan', 'fasilitas', 'kode_penitipan'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Penitipan';
        $penitipan = $this->penitipanModel->find($id);

        if (!$penitipan) {
            return redirect()->to('admin/penitipan')->with('error', 'Data penitipan tidak ditemukan');
        }

        // Get pelanggan and hewan data
        $pelanggan_list = $this->pelangganModel->findAll();

        // Get specific pelanggan data
        $pelanggan = null;
        if ($penitipan['idpelanggan']) {
            $pelanggan = $this->pelangganModel->find($penitipan['idpelanggan']);
        } else {
            // Default data jika tidak ada pelanggan
            $pelanggan = [
                'idpelanggan' => '',
                'nama' => 'Pelanggan Umum'
            ];
        }

        // Get detail penitipan with all info needed
        $detail_penitipan = $this->db->table('detailpenitipan dp')
            ->select('dp.*, f.namafasilitas, f.kategori, f.satuan, h.namahewan, h.jenis')
            ->join('fasilitas f', 'f.kdfasilitas = dp.kdfasilitas')
            ->join('hewan h', 'h.idhewan = dp.idhewan')
            ->where('dp.kdpenitipan', $id)
            ->get()->getResultArray();

        // Get hewan info for this penitipan (assuming one penitipan has one hewan)
        $hewan = null;
        if (!empty($detail_penitipan)) {
            $hewan = $this->hewanModel->find($detail_penitipan[0]['idhewan']);
        } else {
            // Default data jika tidak ada detail
            $hewan = [
                'idhewan' => '',
                'namahewan' => ''
            ];
        }

        // Get fasilitas list
        $fasilitas = $this->fasilitasModel->getWithKategori();

        return view('admin/penitipan/edit', compact(
            'title',
            'penitipan',
            'pelanggan_list',
            'pelanggan',
            'hewan',
            'fasilitas',
            'detail_penitipan'
        ));
    }

    public function getPenitipan()
    {
        $pelangganFilter = $this->request->getGet('idpelanggan') ?? '';
        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? '';
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? '';
        $statusFilter = $this->request->getGet('status') ?? '';

        $builder = $this->db->table('penitipan');
        $builder->select('penitipan.kdpenitipan, penitipan.tglpenitipan, penitipan.tglselesai, penitipan.durasi, penitipan.grandtotal, penitipan.status, penitipan.idpelanggan');
        $builder->select('IFNULL(pelanggan.nama, "Pelanggan Umum") as nama', false);
        $builder->select('(SELECT h.namahewan FROM detailpenitipan dp JOIN hewan h ON h.idhewan = dp.idhewan WHERE dp.kdpenitipan = penitipan.kdpenitipan LIMIT 1) as namahewan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penitipan.idpelanggan', 'left');
        $builder->groupBy('penitipan.kdpenitipan');
        $builder->orderBy('penitipan.kdpenitipan', 'DESC');

        if (!empty($pelangganFilter)) {
            $builder->where('penitipan.idpelanggan', $pelangganFilter);
        }

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $builder->where('penitipan.tglpenitipan >=', $tanggalMulai);
            $builder->where('penitipan.tglpenitipan <=', $tanggalAkhir);
        }

        if ($statusFilter !== '') {
            $builder->where('penitipan.status', $statusFilter);
        }

        // Gunakan DataTable dengan konfigurasi pencarian manual
        $dt = new DataTable($builder);

        // Nonaktifkan pencarian global otomatis
        $dt->setSearchableColumns(['penitipan.kdpenitipan']);

        // Format data
        $dt->format('tglpenitipan', function ($value) {
            return date('d/m/Y', strtotime($value));
        })
            ->format('tglselesai', function ($value) {
                return date('d/m/Y', strtotime($value));
            })
            ->format('grandtotal', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('status', function ($value) {
                $statusLabels = [
                    0 => '<span class="badge bg-warning">Pending</span>',
                    1 => '<span class="badge bg-primary">Dalam Penitipan</span>',
                    2 => '<span class="badge bg-success">Selesai</span>'
                ];
                return $statusLabels[$value] ?? '<span class="badge bg-secondary">Unknown</span>';
            });

        // Tambahkan kolom aksi
        $dt->add('action', function ($row) {
            return '<div class="d-flex gap-1">
                <a href="' . site_url('admin/penitipan/edit/' . $row->kdpenitipan) . '" class="btn btn-sm btn-info">
                    <i class="bi bi-pencil-square"></i>
                </a>
                <button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdpenitipan . '">
                    <i class="bi bi-trash"></i>
                </button>
                <a href="' . site_url('admin/penitipan/detail/' . $row->kdpenitipan) . '" class="btn btn-sm btn-primary">
                    <i class="bi bi-eye"></i>
                </a>
                <a href="' . site_url('admin/penitipan/cetak/' . $row->kdpenitipan) . '" class="btn btn-sm btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>
            </div>';
        });

        return $dt->toJson(true);
    }

    public function getNextKdPenitipan()
    {
        $nextId = $this->penitipanModel->generateKdPenitipan();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdpenitipan' => $nextId]
        ]);
    }

    public function getPenitipanById($id = null)
    {
        $data = $this->penitipanModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data penitipan tidak ditemukan'
        ]);
    }

    public function getDetailPenitipan($kdpenitipan)
    {
        $data = $this->detailPenitipanModel->getDetailWithInfo($kdpenitipan);

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

    public function getHewanById($id = null)
    {
        $data = $this->hewanModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data hewan tidak ditemukan'
        ]);
    }

    public function getFasilitasById($id = null)
    {
        $data = $this->fasilitasModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data fasilitas tidak ditemukan'
        ]);
    }

    public function addPenitipan()
    {
        $this->db->transStart();

        try {
            $tglMasuk = $this->request->getPost('tglmasuk');
            $durasi = (int)$this->request->getPost('durasi');
            $tglKeluar = $this->request->getPost('tglkeluar');
            $idPelanggan = $this->request->getPost('idpelanggan');
            $idHewan = $this->request->getPost('idhewan');
            $catatan = $this->request->getPost('catatan');
            $total = $this->request->getPost('total');
            $kdPenitipan = $this->request->getPost('kdpenitipan');

            // Validasi data
            if (empty($kdPenitipan) || empty($tglMasuk) || empty($tglKeluar) || empty($durasi)) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data penitipan tidak lengkap'
                ]);
            }

            // Simpan data penitipan
            $penitipanData = [
                'kdpenitipan' => $kdPenitipan,
                'tglpenitipan' => $tglMasuk,
                'tglselesai' => $tglKeluar,
                'durasi' => $durasi,
                'idpelanggan' => $idPelanggan,
                'grandtotal' => $total,
                'status' => 1, // Status awal: Dalam Penitipan (langsung)
                'keterangan' => $catatan
            ];

            $result = $this->penitipanModel->insert($penitipanData);

            if (!$result) {
                throw new \Exception('Gagal menyimpan data penitipan');
            }

            // Simpan detail fasilitas
            $fasilitas = $this->request->getPost('fasilitas');
            if (is_array($fasilitas)) {
                foreach ($fasilitas as $item) {
                    if (!empty($item['kdfasilitas'])) {
                        $detailData = [
                            'kdpenitipan' => $kdPenitipan,
                            'idhewan' => $idHewan,
                            'kdfasilitas' => $item['kdfasilitas'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga'],
                            'totalharga' => $item['subtotal']
                        ];
                        $resultDetail = $this->detailPenitipanModel->insert($detailData);

                        if (!$resultDetail) {
                            throw new \Exception('Gagal menyimpan detail fasilitas');
                        }
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menyimpan data penitipan');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penitipan berhasil disimpan',
                'data' => [
                    'kdpenitipan' => $kdPenitipan,
                    'print_url' => site_url('admin/penitipan/cetak/' . $kdPenitipan)
                ]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id = null)
    {
        $this->db->transStart();

        try {
            $tglMasuk = $this->request->getPost('tglmasuk');
            $durasi = (int)$this->request->getPost('durasi');
            $tglKeluar = $this->request->getPost('tglkeluar');
            $idPelanggan = $this->request->getPost('idpelanggan');
            $idHewan = $this->request->getPost('idhewan');
            $catatan = $this->request->getPost('catatan');
            $total = $this->request->getPost('total');
            $status = $this->request->getPost('status');

            // Cek apakah data penitipan ada
            $penitipan = $this->penitipanModel->find($id);
            if (!$penitipan) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data penitipan tidak ditemukan'
                ]);
            }

            // Update data penitipan
            $penitipanData = [
                'kdpenitipan' => $id,
                'tglpenitipan' => $tglMasuk,
                'tglselesai' => $tglKeluar,
                'durasi' => $durasi,
                'idpelanggan' => $idPelanggan,
                'grandtotal' => $total,
                'status' => $status,
                'keterangan' => $catatan
            ];

            $this->penitipanModel->update($id, $penitipanData);

            // Hapus semua detail lama
            $this->detailPenitipanModel->where('kdpenitipan', $id)->delete();

            // Simpan detail fasilitas baru
            $fasilitas = $this->request->getPost('fasilitas');
            if (is_array($fasilitas)) {
                foreach ($fasilitas as $item) {
                    if (!empty($item['kdfasilitas'])) {
                        $detailData = [
                            'kdpenitipan' => $id,
                            'idhewan' => $idHewan,
                            'kdfasilitas' => $item['kdfasilitas'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga'],
                            'totalharga' => $item['subtotal']
                        ];
                        $this->detailPenitipanModel->insert($detailData);
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat memperbarui data penitipan.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penitipan berhasil diperbarui',
                'data' => [
                    'kdpenitipan' => $id,
                    'print_url' => site_url('admin/penitipan/cetak/' . $id)
                ]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function deletePenitipan($id = null)
    {
        $this->db->transStart();

        try {
            // Cek apakah data exists
            $existingPenitipan = $this->penitipanModel->find($id);
            if (!$existingPenitipan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data penitipan tidak ditemukan'
                ]);
            }

            // Hapus detail
            $this->detailPenitipanModel->where('kdpenitipan', $id)->delete();

            // Hapus header
            $this->penitipanModel->delete($id);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menghapus data');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penitipan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data penitipan: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($id = null)
    {
        $title = 'Detail Penitipan';
        $penitipan = $this->penitipanModel->find($id);

        if (!$penitipan) {
            return redirect()->to('admin/penitipan')->with('error', 'Data penitipan tidak ditemukan');
        }

        // Get pelanggan data
        $pelanggan = null;
        if ($penitipan['idpelanggan']) {
            $pelanggan = $this->pelangganModel->find($penitipan['idpelanggan']);
        }

        // Get detail penitipan with fasilitas and hewan info
        $builder = $this->db->table('detailpenitipan dp');
        $builder->select('dp.*, f.namafasilitas, f.kategori, f.satuan, h.namahewan as nama_hewan, h.jenis as jenis_hewan');
        $builder->join('fasilitas f', 'f.kdfasilitas = dp.kdfasilitas', 'left');
        $builder->join('hewan h', 'h.idhewan = dp.idhewan', 'left');
        $builder->where('dp.kdpenitipan', $id);
        $detailPenitipan = $builder->get()->getResultArray();

        return view('admin/penitipan/detail', compact('title', 'penitipan', 'pelanggan', 'detailPenitipan'));
    }

    public function cetak($id = null)
    {
        $penitipan = $this->penitipanModel->getWithPelanggan($id);

        if (!$penitipan) {
            return redirect()->to('admin/penitipan')->with('error', 'Data penitipan tidak ditemukan');
        }

        $detailPenitipan = $this->detailPenitipanModel->getDetailWithInfo($id);

        // Mencegah header dan footer dari view layout utama ditampilkan
        return view('admin/penitipan/cetak', compact('penitipan', 'detailPenitipan'));
    }

    // Function to handle change status 
    public function changeStatus($id)
    {
        $this->db->transStart();

        try {
            $penitipan = $this->penitipanModel->find($id);

            if (!$penitipan) {
                return $this->response->setStatusCode(404)->setJSON([
                    'status' => 'error',
                    'message' => 'Data penitipan tidak ditemukan'
                ]);
            }

            // Status cycle: 0 (Pending) -> 1 (Dalam Penitipan) -> 2 (Selesai) -> 0 (Pending)
            $newStatus = ($penitipan['status'] + 1) % 3;

            // Update status
            $this->penitipanModel->update($id, ['status' => $newStatus]);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat mengubah status');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Status penitipan berhasil diubah',
                'new_status' => $newStatus,
                'new_status_label' => $this->penitipanModel->getStatusLabel($newStatus)
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            return $this->response->setStatusCode(400)->setJSON([
                'status' => 'error',
                'message' => 'Gagal mengubah status penitipan: ' . $e->getMessage()
            ]);
        }
    }

    public function store()
    {
        $this->db->transStart();

        try {
            $tglMasuk = $this->request->getPost('tglmasuk');
            $durasi = (int)$this->request->getPost('durasi');
            $tglKeluar = $this->request->getPost('tglkeluar');
            $idPelanggan = $this->request->getPost('idpelanggan');
            $idHewan = $this->request->getPost('idhewan');
            $catatan = $this->request->getPost('catatan');
            $total = $this->request->getPost('total');
            $kdPenitipan = $this->request->getPost('kdpenitipan');

            // Simpan data penitipan
            $penitipanData = [
                'kdpenitipan' => $kdPenitipan,
                'tglpenitipan' => $tglMasuk,
                'tglselesai' => $tglKeluar,
                'durasi' => $durasi,
                'idpelanggan' => $idPelanggan,
                'grandtotal' => $total,
                'status' => 1, // Status awal: Dalam Penitipan (langsung)
                'keterangan' => $catatan
            ];

            $this->penitipanModel->insert($penitipanData);

            // Simpan detail fasilitas
            $fasilitas = $this->request->getPost('fasilitas');
            if (is_array($fasilitas)) {
                foreach ($fasilitas as $item) {
                    if (!empty($item['kdfasilitas'])) {
                        $detailData = [
                            'kdpenitipan' => $kdPenitipan,
                            'idhewan' => $idHewan,
                            'kdfasilitas' => $item['kdfasilitas'],
                            'jumlah' => $item['jumlah'],
                            'harga' => $item['harga'],
                            'totalharga' => $item['subtotal']
                        ];
                        $this->detailPenitipanModel->insert($detailData);
                    }
                }
            }

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Terjadi kesalahan saat menyimpan data penitipan.'
                ]);
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penitipan berhasil disimpan',
                'data' => [
                    'kdpenitipan' => $kdPenitipan,
                    'print_url' => site_url('admin/penitipan/cetak/' . $kdPenitipan)
                ]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }

    public function getHewanByPelanggan($idPelanggan)
    {
        $hewan = $this->hewanModel->where('idpelanggan', $idPelanggan)->findAll();

        if ($hewan) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $hewan
            ]);
        }

        return $this->response->setJSON([
            'status' => 'error',
            'message' => 'Tidak ada data hewan untuk pelanggan ini',
            'data' => []
        ]);
    }

    // Metode untuk menghitung denda keterlambatan
    public function hitungDenda()
    {
        $kdpenitipan = $this->request->getPost('kdpenitipan');
        $tglPenjemputan = $this->request->getPost('tglpenjemputan');

        if (!$kdpenitipan) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'ID penitipan tidak ditemukan'
            ]);
        }

        // Hitung denda
        $hasil = $this->penitipanModel->hitungDenda($kdpenitipan, $tglPenjemputan);

        if (!$hasil['status']) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => $hasil['message']
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $hasil
        ]);
    }

    // Metode untuk menyimpan data penjemputan dan denda
    public function penjemputan()
    {
        $this->db->transStart();

        try {
            $kdpenitipan = $this->request->getPost('kdpenitipan');
            $tglpenjemputan = $this->request->getPost('tglpenjemputan');

            // Cek apakah data penitipan ada
            $penitipan = $this->penitipanModel->find($kdpenitipan);
            if (!$penitipan) {
                return $this->response->setJSON([
                    'status' => 'error',
                    'message' => 'Data penitipan tidak ditemukan'
                ]);
            }

            // Hitung denda terlebih dahulu
            $dendaResult = $this->penitipanModel->hitungDenda($kdpenitipan, $tglpenjemputan);

            // Simpan data penjemputan dan denda
            $updateData = [
                'tglpenjemputan' => $tglpenjemputan,
                'status' => 2, // Status: Selesai
                'is_terlambat' => $dendaResult['is_terlambat'],
                'jumlah_hari_terlambat' => $dendaResult['jumlah_hari_terlambat'],
                'biaya_denda' => $dendaResult['biaya_denda'],
                'total_biaya_dengan_denda' => $dendaResult['total_biaya_dengan_denda']
            ];

            $this->penitipanModel->update($kdpenitipan, $updateData);

            $this->db->transComplete();

            if ($this->db->transStatus() === FALSE) {
                throw new \Exception('Terjadi kesalahan saat menyimpan data penjemputan');
            }

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data penjemputan berhasil disimpan',
                'data' => [
                    'kdpenitipan' => $kdpenitipan,
                    'tglpenjemputan' => $tglpenjemputan,
                    'is_terlambat' => $dendaResult['is_terlambat'],
                    'jumlah_hari_terlambat' => $dendaResult['jumlah_hari_terlambat'],
                    'biaya_denda' => $dendaResult['biaya_denda'],
                    'total_biaya_dengan_denda' => $dendaResult['total_biaya_dengan_denda']
                ]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Terjadi kesalahan: ' . $e->getMessage()
            ]);
        }
    }
}
