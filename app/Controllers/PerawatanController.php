<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Models\PerawatanModel;
use App\Models\DetailPerawatanModel;
use App\Models\HewanModel;
use App\Models\PelangganModel;
use App\Models\FasilitasModel;
use Hermawan\DataTables\DataTable;

class PerawatanController extends BaseController
{
    protected $perawatanModel;
    protected $detailPerawatanModel;
    protected $hewanModel;
    protected $pelangganModel;
    protected $fasilitasModel;
    protected $db;

    public function __construct()
    {
        $this->perawatanModel = new PerawatanModel();
        $this->detailPerawatanModel = new DetailPerawatanModel();
        $this->hewanModel = new HewanModel();
        $this->pelangganModel = new PelangganModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->db = \Config\Database::connect();
    }

    public function index()
    {
        $title = 'Manajemen Perawatan';
        $pelanggan = $this->pelangganModel->findAll();
        return view('admin/perawatan/index', compact('title', 'pelanggan'));
    }

    public function create()
    {
        $title = 'Tambah Perawatan';
        $pelanggan = $this->pelangganModel->findAll();
        $hewan = $this->hewanModel->findAll();
        $fasilitas = $this->fasilitasModel->getWithKategoriPerawatan();
        $kode_perawatan = $this->perawatanModel->generateKdPerawatan();
        return view('admin/perawatan/create', compact('title', 'pelanggan', 'hewan', 'fasilitas', 'kode_perawatan'));
    }

    public function edit($id = null)
    {
        $title = 'Edit Perawatan';
        $perawatan = $this->perawatanModel->getWithPelanggan($id);

        if (!$perawatan) {
            return redirect()->to('admin/perawatan')->with('error', 'Data perawatan tidak ditemukan');
        }

        // Get pelanggan and hewan data
        $pelanggan_list = $this->pelangganModel->findAll();

        // Get specific pelanggan data
        $pelanggan = null;
        if ($perawatan['idpelanggan']) {
            $pelanggan = $this->pelangganModel->find($perawatan['idpelanggan']);
        } else {
            // Default data jika tidak ada pelanggan
            $pelanggan = [
                'idpelanggan' => '',
                'nama' => 'Pelanggan Umum'
            ];
        }

        // Get detail perawatan with all info needed
        $detail_perawatan = $this->detailPerawatanModel->getDetailWithInfo($id);

        // Get hewan list for this pelanggan
        $hewan_list = [];
        if ($perawatan['idpelanggan']) {
            $hewan_list = $this->hewanModel->where('idpelanggan', $perawatan['idpelanggan'])->findAll();
        }

        // Get fasilitas list
        $fasilitas = $this->fasilitasModel->getWithKategoriPerawatan();

        return view('admin/perawatan/edit', compact(
            'title',
            'perawatan',
            'pelanggan_list',
            'pelanggan',
            'hewan_list',
            'fasilitas',
            'detail_perawatan'
        ));
    }

    public function getPerawatan()
    {
        $pelangganFilter = $this->request->getGet('idpelanggan') ?? '';
        $tanggalMulai = $this->request->getGet('tanggal_mulai') ?? '';
        $tanggalAkhir = $this->request->getGet('tanggal_akhir') ?? '';
        $statusFilter = $this->request->getGet('status') ?? '';

        $builder = $this->db->table('perawatan');
        $builder->select('perawatan.kdperawatan, perawatan.tglperawatan, perawatan.grandtotal, perawatan.status, perawatan.idpelanggan, perawatan.idhewan');
        $builder->select('IFNULL(pelanggan.nama, "Pelanggan Umum") as nama', false);
        $builder->select('hewan.namahewan as namahewan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = perawatan.idpelanggan', 'left');
        $builder->join('hewan', 'hewan.idhewan = perawatan.idhewan', 'left');
        $builder->groupBy('perawatan.kdperawatan');
        $builder->orderBy('perawatan.kdperawatan', 'DESC');

        if (!empty($pelangganFilter)) {
            $builder->where('perawatan.idpelanggan', $pelangganFilter);
        }

        if (!empty($tanggalMulai) && !empty($tanggalAkhir)) {
            $builder->where('perawatan.tglperawatan >=', $tanggalMulai);
            $builder->where('perawatan.tglperawatan <=', $tanggalAkhir);
        }

        if ($statusFilter !== '') {
            $builder->where('perawatan.status', $statusFilter);
        }

        // Gunakan DataTable dengan konfigurasi pencarian manual
        $dt = new DataTable($builder);

        // Nonaktifkan pencarian global otomatis
        $dt->setSearchableColumns(['perawatan.kdperawatan']);

        // Format data
        $dt->format('tglperawatan', function ($value) {
            return date('d/m/Y', strtotime($value));
        })
            ->format('grandtotal', function ($value) {
                return 'Rp ' . number_format($value, 0, ',', '.');
            })
            ->format('status', function ($value) {
                $statusLabels = [
                    0 => '<span class="badge bg-warning">Pending</span>',
                    1 => '<span class="badge bg-primary">Dalam Proses</span>',
                    2 => '<span class="badge bg-success">Selesai</span>'
                ];
                return $statusLabels[$value] ?? '<span class="badge bg-secondary">Unknown</span>';
            });

        // Tambahkan kolom aksi
        $dt->add('action', function ($row) {
            $editBtn = '<a href="' . site_url('admin/perawatan/edit/' . $row->kdperawatan) . '" class="btn btn-sm btn-info">
                <i class="bi bi-pencil-square"></i>
            </a>';

            $deleteBtn = '<button class="btn btn-sm btn-danger btn-delete" data-id="' . $row->kdperawatan . '">
                <i class="bi bi-trash"></i>
            </button>';

            $detailBtn = '<a href="' . site_url('admin/perawatan/detail/' . $row->kdperawatan) . '" class="btn btn-sm btn-primary">
                <i class="bi bi-eye"></i>
            </a>';

            $printBtn = '';
            if ($row->status == 2) { // Hanya tampilkan tombol cetak jika status = 2 (Selesai)
                $printBtn = '<a href="' . site_url('admin/perawatan/cetak/' . $row->kdperawatan) . '" class="btn btn-sm btn-success" target="_blank">
                    <i class="bi bi-printer"></i>
                </a>';
            }

            return '<div class="d-flex gap-1">
                ' . $editBtn . $deleteBtn . $detailBtn . $printBtn . '
            </div>';
        });

        return $dt->toJson(true);
    }

    public function getNextKdPerawatan()
    {
        $nextId = $this->perawatanModel->generateKdPerawatan();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => ['kdperawatan' => $nextId]
        ]);
    }

    public function getPerawatanById($id = null)
    {
        $data = $this->perawatanModel->find($id);

        if ($data) {
            return $this->response->setJSON([
                'status' => 'success',
                'data' => $data
            ]);
        }

        return $this->response->setStatusCode(404)->setJSON([
            'status' => 'error',
            'message' => 'Data perawatan tidak ditemukan'
        ]);
    }

    public function getDetailPerawatan($kdperawatan)
    {
        // Ambil detail perawatan menggunakan query builder
        $builder = $this->db->table('detailperawatan d');
        $builder->select('d.*, f.namafasilitas, f.kategori, f.satuan');
        $builder->join('fasilitas f', 'f.kdfasilitas = d.detailkdfasilitas', 'left');
        $builder->where('d.detailkdperawatan', $kdperawatan);
        $data = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
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

    public function addPerawatan()
    {
        $this->db->transStart();

        try {
            // Generate kode perawatan
            $kdperawatan = $this->perawatanModel->generateKdPerawatan();

            $data = [
                'kdperawatan' => $kdperawatan,
                'tglperawatan' => $this->request->getPost('tglperawatan'),
                'idpelanggan' => $this->request->getPost('idpelanggan') ?: null,
                'idhewan' => $this->request->getPost('idhewan') ?: null,
                'grandtotal' => $this->request->getPost('grandtotal'),
                'status' => $this->request->getPost('status') ?? 0,
                'keterangan' => $this->request->getPost('keterangan'),
            ];

            // Simpan header perawatan menggunakan query builder langsung
            $this->db->table('perawatan')->insert($data);
            log_message('debug', 'Menyimpan header perawatan: ' . json_encode($data));

            // Verifikasi data perawatan tersimpan
            $perawatanCheck = $this->db->table('perawatan')->where('kdperawatan', $kdperawatan)->get()->getRowArray();
            if (!$perawatanCheck) {
                throw new \Exception('Data perawatan gagal disimpan');
            }
            log_message('debug', 'Verifikasi header perawatan berhasil: ' . json_encode($perawatanCheck));

            // Simpan detail perawatan
            $detailKdFasilitas = $this->request->getPost('detailkdfasilitas');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');

            if (is_array($detailKdFasilitas) && count($detailKdFasilitas) > 0) {
                for ($i = 0; $i < count($detailKdFasilitas); $i++) {
                    $detailData = [
                        'detailkdperawatan' => $kdperawatan,
                        'detailkdfasilitas' => $detailKdFasilitas[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                    ];

                    // Debug log
                    log_message('debug', 'Menyimpan detail perawatan: ' . json_encode($detailData));

                    // Gunakan query builder langsung untuk insert
                    $this->db->table('detailperawatan')->insert($detailData);
                }
            }

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data perawatan berhasil disimpan',
                'data' => ['kdperawatan' => $kdperawatan]
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            log_message('error', 'Error saat menyimpan perawatan: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menyimpan data perawatan: ' . $e->getMessage()
            ]);
        }
    }

    public function update($id = null)
    {
        $this->db->transStart();

        try {
            $data = [
                'kdperawatan' => $id,
                'tglperawatan' => $this->request->getPost('tglperawatan'),
                'idpelanggan' => $this->request->getPost('idpelanggan') ?: null,
                'idhewan' => $this->request->getPost('idhewan') ?: null,
                'grandtotal' => $this->request->getPost('grandtotal'),
                'status' => $this->request->getPost('status'),
                'keterangan' => $this->request->getPost('keterangan'),
            ];

            // Update header perawatan menggunakan query builder langsung
            $this->db->table('perawatan')->where('kdperawatan', $id)->update($data);
            log_message('debug', 'Update header perawatan: ' . json_encode($data));

            // Hapus detail perawatan lama
            $this->db->table('detailperawatan')->where('detailkdperawatan', $id)->delete();

            // Simpan detail perawatan baru
            $detailKdFasilitas = $this->request->getPost('detailkdfasilitas');
            $detailJumlah = $this->request->getPost('jumlah');
            $detailHarga = $this->request->getPost('harga');
            $detailTotal = $this->request->getPost('totalharga');

            if (is_array($detailKdFasilitas) && count($detailKdFasilitas) > 0) {
                for ($i = 0; $i < count($detailKdFasilitas); $i++) {
                    $detailData = [
                        'detailkdperawatan' => $id,
                        'detailkdfasilitas' => $detailKdFasilitas[$i],
                        'jumlah' => $detailJumlah[$i],
                        'harga' => $detailHarga[$i],
                        'totalharga' => $detailTotal[$i],
                    ];

                    // Debug log
                    log_message('debug', 'Menyimpan detail perawatan update: ' . json_encode($detailData));

                    // Gunakan query builder langsung untuk insert
                    $this->db->table('detailperawatan')->insert($detailData);
                }
            }

            $this->db->transCommit();

            // Jika request adalah AJAX, kembalikan respons JSON
            if ($this->request->isAJAX()) {
                return $this->response->setJSON([
                    'status' => 'success',
                    'message' => 'Data perawatan berhasil diperbarui'
                ]);
            }

            // Jika bukan AJAX, redirect seperti biasa
            return redirect()->to('admin/perawatan/detail/' . $id)->with('success', 'Data perawatan berhasil diperbarui');
        } catch (\Exception $e) {
            $this->db->transRollback();

            log_message('error', 'Error saat memperbarui perawatan: ' . $e->getMessage());

            // Jika request adalah AJAX, kembalikan respons JSON
            if ($this->request->isAJAX()) {
                return $this->response->setStatusCode(500)->setJSON([
                    'status' => 'error',
                    'message' => 'Gagal memperbarui data perawatan: ' . $e->getMessage()
                ]);
            }

            // Jika bukan AJAX, redirect seperti biasa
            return redirect()->back()->with('error', 'Gagal memperbarui data perawatan: ' . $e->getMessage())->withInput();
        }
    }

    public function deletePerawatan($id = null)
    {
        $this->db->transStart();

        try {
            // Hapus detail perawatan
            $this->db->table('detailperawatan')->where('detailkdperawatan', $id)->delete();

            // Hapus header perawatan
            $this->perawatanModel->delete($id);

            $this->db->transCommit();

            return $this->response->setJSON([
                'status' => 'success',
                'message' => 'Data perawatan berhasil dihapus'
            ]);
        } catch (\Exception $e) {
            $this->db->transRollback();

            log_message('error', 'Error saat menghapus perawatan: ' . $e->getMessage());

            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal menghapus data perawatan: ' . $e->getMessage()
            ]);
        }
    }

    public function detail($id = null)
    {
        $title = 'Detail Perawatan';
        $perawatan = $this->perawatanModel->getWithPelanggan($id);

        if (!$perawatan) {
            return redirect()->to('admin/perawatan')->with('error', 'Data perawatan tidak ditemukan');
        }

        // Ambil detail perawatan menggunakan query builder
        $builder = $this->db->table('detailperawatan d');
        $builder->select('d.*, f.namafasilitas, f.kategori, f.satuan');
        $builder->join('fasilitas f', 'f.kdfasilitas = d.detailkdfasilitas', 'left');
        $builder->where('d.detailkdperawatan', $id);
        $detail_perawatan = $builder->get()->getResultArray();

        return view('admin/perawatan/detail', compact('title', 'perawatan', 'detail_perawatan'));
    }

    public function cetak($id = null)
    {
        $title = 'Cetak Perawatan';
        $perawatan = $this->perawatanModel->getWithPelanggan($id);

        if (!$perawatan) {
            return redirect()->to('admin/perawatan')->with('error', 'Data perawatan tidak ditemukan');
        }

        // Cek status perawatan, hanya bisa cetak jika status = 2 (Selesai)
        if ($perawatan['status'] != 2) {
            return redirect()->to('admin/perawatan/detail/' . $id)->with('error', 'Faktur hanya dapat dicetak ketika status perawatan sudah selesai');
        }

        // Ambil detail perawatan menggunakan query builder
        $builder = $this->db->table('detailperawatan d');
        $builder->select('d.*, f.namafasilitas, f.kategori, f.satuan');
        $builder->join('fasilitas f', 'f.kdfasilitas = d.detailkdfasilitas', 'left');
        $builder->where('d.detailkdperawatan', $id);
        $detail_perawatan = $builder->get()->getResultArray();

        return view('admin/perawatan/cetak', compact('title', 'perawatan', 'detail_perawatan'));
    }

    public function updateStatus()
    {
        $kdperawatan = $this->request->getPost('kdperawatan');
        $status = $this->request->getPost('status');

        try {
            // Update status header perawatan
            $this->perawatanModel->update($kdperawatan, [
                'status' => $status
            ]);

            $response = [
                'status' => 'success',
                'message' => 'Status perawatan berhasil diperbarui',
                'header_status' => $status
            ];

            // Jika status diubah menjadi selesai (2), tambahkan URL cetak untuk redirect
            if ($status == 2) {
                $response['print_url'] = site_url('admin/perawatan/cetak/' . $kdperawatan);
            }

            return $this->response->setJSON($response);
        } catch (\Exception $e) {
            return $this->response->setStatusCode(500)->setJSON([
                'status' => 'error',
                'message' => 'Gagal memperbarui status perawatan: ' . $e->getMessage()
            ]);
        }
    }

    public function getHewanByPelanggan($idPelanggan)
    {
        $hewan = $this->hewanModel->where('idpelanggan', $idPelanggan)->findAll();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $hewan
        ]);
    }

    public function store()
    {
        return $this->addPerawatan();
    }
}
