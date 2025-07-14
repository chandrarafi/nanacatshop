<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\HewanModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\FasilitasModel;
use App\Models\SupplierModel;
use App\Models\BarangMasukModel;
use App\Models\DetailBarangMasukModel;
use Dompdf\Dompdf;
use Dompdf\Options;

class LaporanController extends BaseController
{
    protected $pelangganModel;
    protected $hewanModel;
    protected $barangModel;
    protected $kategoriModel;
    protected $fasilitasModel;
    protected $supplierModel;
    protected $barangMasukModel;
    protected $detailBarangMasukModel;
    protected $db;

    public function initController(\CodeIgniter\HTTP\RequestInterface $request, \CodeIgniter\HTTP\ResponseInterface $response, \Psr\Log\LoggerInterface $logger)
    {
        parent::initController($request, $response, $logger);

        $this->pelangganModel = new PelangganModel();
        $this->hewanModel = new HewanModel();
        $this->barangModel = new BarangModel();
        $this->kategoriModel = new KategoriModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->supplierModel = new SupplierModel();
        $this->barangMasukModel = new BarangMasukModel();
        $this->detailBarangMasukModel = new DetailBarangMasukModel();
        $this->db = \Config\Database::connect();
    }

    public function pelanggan()
    {
        $title = 'Laporan Data Pelanggan';
        return view('admin/laporan/pelanggan', compact('title'));
    }

    public function getPelangganData()
    {
        $jenkel = $this->request->getGet('jenkel');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->pelangganModel->builder();

        // Filter berdasarkan jenis kelamin
        if (!empty($jenkel)) {
            $builder->where('jenkel', $jenkel);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPelangganPdf()
    {
        $jenkel = $this->request->getGet('jenkel');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->pelangganModel->builder();

        // Filter berdasarkan jenis kelamin
        if (!empty($jenkel)) {
            $builder->where('jenkel', $jenkel);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        $pelanggan = $builder->get()->getResult();

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'title' => 'Laporan Data Pelanggan',
            'pelanggan' => $pelanggan,
            'filter' => [
                'jenkel' => $jenkel,
                'tgl_awal' => $tglAwal,
                'tgl_akhir' => $tglAkhir
            ],
            'tanggal_cetak' => date('d-m-Y'),
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/pelanggan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Pelanggan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Data Hewan
    public function hewan()
    {
        $title = 'Laporan Data Hewan';
        return view('admin/laporan/hewan', compact('title'));
    }

    public function getHewanData()
    {
        $jenis = $this->request->getGet('jenis');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->hewanModel->builder();
        $builder->select('hewan.*, pelanggan.nama as nama_pelanggan');
        $builder->join('pelanggan', 'pelanggan.idpelanggan = hewan.idpelanggan');

        // Filter berdasarkan jenis hewan
        if (!empty($jenis)) {
            $builder->where('hewan.jenis', $jenis);
        }

        // Filter berdasarkan pemilik
        if (!empty($idpelanggan)) {
            $builder->where('hewan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('hewan.created_at >=', $tglAwal . ' 00:00:00')
                ->where('hewan.created_at <=', $tglAkhir . ' 23:59:59');
        }

        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getPelangganList()
    {
        $pelanggan = $this->pelangganModel->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $pelanggan
        ]);
    }

    // Endpoint untuk data pelanggan di modal
    public function getPelangganForModal()
    {
        $pelanggan = $this->pelangganModel->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $pelanggan
        ]);
    }

    public function cetakHewanPdf()
    {
        $jenis = $this->request->getGet('jenis');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->hewanModel->builder();
        $builder->select('hewan.*, pelanggan.nama as nama_pelanggan');
        $builder->join('pelanggan', 'pelanggan.idpelanggan = hewan.idpelanggan');

        // Filter berdasarkan jenis hewan
        if (!empty($jenis)) {
            $builder->where('hewan.jenis', $jenis);
        }

        // Filter berdasarkan pemilik
        if (!empty($idpelanggan)) {
            $builder->where('hewan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('hewan.created_at >=', $tglAwal . ' 00:00:00')
                ->where('hewan.created_at <=', $tglAkhir . ' 23:59:59');
        }

        $hewan = $builder->get()->getResult();

        // Dapatkan nama pemilik jika ada filter pemilik
        $namaPemilik = '';
        if (!empty($idpelanggan)) {
            $pemilik = $this->pelangganModel->find($idpelanggan);
            if ($pemilik) {
                $namaPemilik = $pemilik['nama'];
            }
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'title' => 'Laporan Data Hewan',
            'hewan' => $hewan,
            'filter' => [
                'jenis' => $jenis,
                'idpelanggan' => $idpelanggan,
                'nama_pemilik' => $namaPemilik,
                'tgl_awal' => $tglAwal,
                'tgl_akhir' => $tglAkhir
            ],
            'tanggal_cetak' => date('d-m-Y'),
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/hewan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL
        $options->set('defaultFont', 'Arial');
        $options->set('defaultMediaType', 'screen');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultEncoding', 'utf-8');

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Hewan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Data Barang
    public function barang()
    {
        $title = 'Laporan Data Barang';
        return view('admin/laporan/barang', compact('title'));
    }

    public function getKategoriList()
    {
        $kategori = $this->kategoriModel->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $kategori
        ]);
    }

    public function getBarangData()
    {
        $kdkategori = $this->request->getGet('kdkategori');
        $stok = $this->request->getGet('stok');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->barangModel->builder();
        $builder->select('barang.*, kategori.namakategori');
        $builder->join('kategori', 'kategori.kdkategori = barang.kdkategori');

        // Filter berdasarkan kategori
        if (!empty($kdkategori)) {
            $builder->where('barang.kdkategori', $kdkategori);
        }

        // Filter berdasarkan stok
        if (!empty($stok)) {
            if ($stok === 'habis') {
                $builder->where('barang.jumlah', 0);
            } else if ($stok === 'tersedia') {
                $builder->where('barang.jumlah >', 0);
            } else if ($stok === 'menipis') {
                $builder->where('barang.jumlah >', 0);
                $builder->where('barang.jumlah <=', 5);
            }
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('barang.created_at >=', $tglAwal . ' 00:00:00')
                ->where('barang.created_at <=', $tglAkhir . ' 23:59:59');
        }

        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakBarangPdf()
    {
        $kdkategori = $this->request->getGet('kdkategori');
        $stok = $this->request->getGet('stok');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->barangModel->builder();
        $builder->select('barang.*, kategori.namakategori');
        $builder->join('kategori', 'kategori.kdkategori = barang.kdkategori');

        // Filter berdasarkan kategori
        if (!empty($kdkategori)) {
            $builder->where('barang.kdkategori', $kdkategori);
        }

        // Filter berdasarkan stok
        if (!empty($stok)) {
            if ($stok === 'habis') {
                $builder->where('barang.jumlah', 0);
            } else if ($stok === 'tersedia') {
                $builder->where('barang.jumlah >', 0);
            } else if ($stok === 'menipis') {
                $builder->where('barang.jumlah >', 0);
                $builder->where('barang.jumlah <=', 5);
            }
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('barang.created_at >=', $tglAwal . ' 00:00:00')
                ->where('barang.created_at <=', $tglAkhir . ' 23:59:59');
        }

        $barang = $builder->get()->getResult();

        // Dapatkan nama kategori jika ada filter kategori
        $namaKategori = '';
        if (!empty($kdkategori)) {
            $kategori = $this->kategoriModel->find($kdkategori);
            if ($kategori) {
                $namaKategori = $kategori['namakategori'];
            }
        }

        // Status stok
        $statusStok = '';
        if (!empty($stok)) {
            if ($stok === 'habis') {
                $statusStok = 'Stok Habis';
            } else if ($stok === 'tersedia') {
                $statusStok = 'Stok Tersedia';
            } else if ($stok === 'menipis') {
                $statusStok = 'Stok Menipis';
            }
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'title' => 'Laporan Data Barang',
            'barang' => $barang,
            'filter' => [
                'kdkategori' => $kdkategori,
                'nama_kategori' => $namaKategori,
                'status_stok' => $statusStok,
                'tgl_awal' => $tglAwal,
                'tgl_akhir' => $tglAkhir
            ],
            'tanggal_cetak' => date('d-m-Y'),
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/barang_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL
        $options->set('defaultFont', 'Arial');
        $options->set('defaultMediaType', 'screen');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultEncoding', 'utf-8');

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Barang_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Data Fasilitas
    public function fasilitas()
    {
        $title = 'Laporan Data Fasilitas';
        return view('admin/laporan/fasilitas', compact('title'));
    }

    public function getFasilitasData()
    {
        $kategori = $this->request->getGet('kategori');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->fasilitasModel->builder();

        // Filter berdasarkan kategori
        if (!empty($kategori)) {
            $builder->where('kategori', $kategori);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getKategoriFasilitas()
    {
        // Dapatkan kategori unik dari tabel fasilitas
        $builder = $this->fasilitasModel->builder();
        $builder->select('kategori');
        $builder->distinct();
        $builder->orderBy('kategori', 'ASC');
        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakFasilitasPdf()
    {
        $kategori = $this->request->getGet('kategori');
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');

        $builder = $this->fasilitasModel->builder();

        // Filter berdasarkan kategori
        if (!empty($kategori)) {
            $builder->where('kategori', $kategori);
        }

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        $fasilitas = $builder->get()->getResult();

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'title' => 'Laporan Data Fasilitas',
            'fasilitas' => $fasilitas,
            'filter' => [
                'kategori' => $kategori,
                'tgl_awal' => $tglAwal,
                'tgl_akhir' => $tglAkhir
            ],
            'tanggal_cetak' => date('d-m-Y'),
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/fasilitas_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL
        $options->set('defaultFont', 'Arial');
        $options->set('defaultMediaType', 'screen');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultEncoding', 'utf-8');

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Fasilitas_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Data Supplier
    public function supplier()
    {
        $title = 'Laporan Data Supplier';
        return view('admin/laporan/supplier', compact('title'));
    }

    public function getSupplierData()
    {
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $keyword = $this->request->getGet('keyword');

        $builder = $this->supplierModel->builder();

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        // Filter berdasarkan keyword
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('namaspl', $keyword)
                ->orLike('kdspl', $keyword)
                ->orLike('nohp', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }

        $data = $builder->get()->getResult();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakSupplierPdf()
    {
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $keyword = $this->request->getGet('keyword');

        $builder = $this->supplierModel->builder();

        // Filter berdasarkan tanggal
        if (!empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('created_at >=', $tglAwal . ' 00:00:00')
                ->where('created_at <=', $tglAkhir . ' 23:59:59');
        }

        // Filter berdasarkan keyword
        if (!empty($keyword)) {
            $builder->groupStart()
                ->like('namaspl', $keyword)
                ->orLike('kdspl', $keyword)
                ->orLike('nohp', $keyword)
                ->orLike('email', $keyword)
                ->groupEnd();
        }

        $supplier = $builder->get()->getResult();

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $data = [
            'title' => 'Laporan Data Supplier',
            'supplier' => $supplier,
            'filter' => [
                'keyword' => $keyword,
                'tgl_awal' => $tglAwal,
                'tgl_akhir' => $tglAkhir
            ],
            'tanggal_cetak' => date('d-m-Y'),
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/supplier_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL
        $options->set('defaultFont', 'Arial');
        $options->set('defaultMediaType', 'screen');
        $options->set('isFontSubsettingEnabled', true);
        $options->set('defaultEncoding', 'utf-8');

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html, 'UTF-8');
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Supplier_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Barang Masuk
    public function barangMasuk()
    {
        $title = 'Laporan Barang Masuk';
        return view('admin/laporan/barang_masuk', compact('title'));
    }

    public function barangMasukPerbulan()
    {
        $title = 'Laporan Barang Masuk Perbulan';
        return view('admin/laporan/barang_masuk_perbulan', compact('title'));
    }

    public function getBarangMasukData()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $kdspl = $this->request->getGet('kdspl');
        $status = $this->request->getGet('status');

        $builder = $this->barangMasukModel->builder();
        $builder->select('barangmasuk.*, supplier.namaspl');
        $builder->join('supplier', 'supplier.kdspl = barangmasuk.kdspl', 'left');

        // Filter berdasarkan supplier
        if (!empty($kdspl)) {
            $builder->where('barangmasuk.kdspl', $kdspl);
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('barangmasuk.status', $status);
        }

        // Filter berdasarkan tipe filter
        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            // Filter berdasarkan rentang tanggal
            $builder->where('DATE(barangmasuk.tglmasuk) >=', $tglAwal)
                ->where('DATE(barangmasuk.tglmasuk) <=', $tglAkhir);
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(barangmasuk.tglmasuk)', $bulan)
                ->where('YEAR(barangmasuk.tglmasuk)', $tahun);
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            // Filter berdasarkan tahun
            $builder->where('YEAR(barangmasuk.tglmasuk)', $tahun);
        }

        $builder->orderBy('barangmasuk.tglmasuk', 'DESC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap barang masuk
        foreach ($data as &$item) {
            $item->detail = $this->detailBarangMasukModel->getDetailWithBarang($item->kdmasuk);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function getSupplierList()
    {
        $supplier = $this->supplierModel->findAll();
        return $this->response->setJSON([
            'status' => 'success',
            'data' => $supplier
        ]);
    }

    public function cetakBarangMasukPdf()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $kdspl = $this->request->getGet('kdspl');
        $status = $this->request->getGet('status');
        $kdmasuk = $this->request->getGet('kdmasuk'); // Tambahkan parameter kdmasuk

        $builder = $this->barangMasukModel->builder();
        $builder->select('barangmasuk.*, supplier.namaspl');
        $builder->join('supplier', 'supplier.kdspl = barangmasuk.kdspl', 'left');

        // Jika ada parameter kdmasuk, filter berdasarkan kode masuk saja
        if (!empty($kdmasuk)) {
            $builder->where('barangmasuk.kdmasuk', $kdmasuk);
        } else {
            // Filter berdasarkan supplier
            if (!empty($kdspl)) {
                $builder->where('barangmasuk.kdspl', $kdspl);
                $supplierInfo = $this->supplierModel->find($kdspl);
                $namaSupplier = $supplierInfo ? $supplierInfo['namaspl'] : '';
            } else {
                $namaSupplier = 'Semua';
            }

            // Filter berdasarkan status
            if ($status !== '' && $status !== null) {
                $builder->where('barangmasuk.status', $status);
            }

            // Filter berdasarkan tipe filter
            $filterText = '';
            if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
                // Filter berdasarkan rentang tanggal
                $builder->where('DATE(barangmasuk.tglmasuk) >=', $tglAwal)
                    ->where('DATE(barangmasuk.tglmasuk) <=', $tglAkhir);
                $filterText = 'Tanggal: ' . date('d-m-Y', strtotime($tglAwal)) . ' s/d ' . date('d-m-Y', strtotime($tglAkhir));
            } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
                // Filter berdasarkan bulan dan tahun
                $builder->where('MONTH(barangmasuk.tglmasuk)', $bulan)
                    ->where('YEAR(barangmasuk.tglmasuk)', $tahun);
                $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
                $filterText = 'Bulan: ' . $namaBulan . ' ' . $tahun;
            } elseif ($filterType == 'tahun' && !empty($tahun)) {
                // Filter berdasarkan tahun
                $builder->where('YEAR(barangmasuk.tglmasuk)', $tahun);
                $filterText = 'Tahun: ' . $tahun;
            } else {
                $filterText = 'Semua Data';
            }
        }

        $builder->orderBy('barangmasuk.tglmasuk', 'DESC');
        $barangMasuk = $builder->get()->getResult();

        // Ambil detail untuk setiap barang masuk
        foreach ($barangMasuk as &$item) {
            $item->detail = $this->detailBarangMasukModel->getDetailWithBarang($item->kdmasuk);
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Pending';
        } elseif ($status === '1') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Jika cetak per kode masuk
        if (!empty($kdmasuk)) {
            $filterText = 'Kode Masuk: ' . $kdmasuk;
            $namaSupplier = $barangMasuk[0]->namaspl ?? 'Tidak ditemukan';
            $statusText = !empty($barangMasuk) ? ($barangMasuk[0]->status == 0 ? 'Pending' : 'Selesai') : '';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Barang Masuk',
            'barangMasuk' => $barangMasuk,
            'filter' => [
                'type' => $filterType,
                'text' => $filterText,
                'supplier' => $namaSupplier ?? 'Semua',
                'status' => $statusText,
                'is_detail' => !empty($kdmasuk), // Tambahkan flag untuk menandai cetak detail
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/barang_masuk_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Barang_Masuk_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function cetakDetailBarangMasuk($kdmasuk)
    {
        return redirect()->to('admin/laporan/barang-masuk/cetak?kdmasuk=' . $kdmasuk);
    }

    public function getBarangMasukPerbulanData()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        if (empty($bulan) || empty($tahun)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Parameter bulan dan tahun diperlukan',
                'data' => []
            ]);
        }

        // Dapatkan semua detail barang masuk untuk bulan dan tahun tertentu
        $builder = $this->db->table('detailbarangmasuk d');
        $builder->select('d.*, b.namabarang, b.satuan, b.hargajual, bm.tglmasuk, bm.kdspl, s.namaspl');
        $builder->join('barangmasuk bm', 'bm.kdmasuk = d.detailkdmasuk', 'left');
        $builder->join('barang b', 'b.kdbarang = d.detailkdbarang', 'left');
        $builder->join('supplier s', 's.kdspl = bm.kdspl', 'left');
        $builder->where('MONTH(bm.tglmasuk)', $bulan);
        $builder->where('YEAR(bm.tglmasuk)', $tahun);
        $builder->orderBy('bm.tglmasuk', 'ASC');
        $builder->orderBy('d.detailkdbarang', 'ASC');

        $data = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakBarangMasukPerbulanPdf()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');

        if (empty($bulan) || empty($tahun)) {
            return redirect()->back()->with('error', 'Parameter bulan dan tahun diperlukan');
        }

        // Dapatkan semua detail barang masuk untuk bulan dan tahun tertentu
        $builder = $this->db->table('detailbarangmasuk d');
        $builder->select('d.*, b.namabarang, b.satuan, b.hargajual, bm.tglmasuk, bm.kdspl, s.namaspl');
        $builder->join('barangmasuk bm', 'bm.kdmasuk = d.detailkdmasuk', 'left');
        $builder->join('barang b', 'b.kdbarang = d.detailkdbarang', 'left');
        $builder->join('supplier s', 's.kdspl = bm.kdspl', 'left');
        $builder->where('MONTH(bm.tglmasuk)', $bulan);
        $builder->where('YEAR(bm.tglmasuk)', $tahun);
        $builder->orderBy('bm.tglmasuk', 'ASC');
        $builder->orderBy('d.detailkdbarang', 'ASC');

        $barangMasuk = $builder->get()->getResultArray();

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Format nama bulan
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 1));
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];
        $namaBulan = $bulanIndo[$namaBulan] ?? $namaBulan;

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Barang Masuk Perbulan',
            'barangMasuk' => $barangMasuk,
            'filter' => [
                'bulan' => $namaBulan,
                'tahun' => $tahun,
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/barang_masuk_perbulan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Barang_Masuk_' . $namaBulan . '_' . $tahun . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Penjualan
    public function penjualan()
    {
        $title = 'Laporan Penjualan';
        return view('admin/laporan/penjualan', compact('title'));
    }

    public function getPenjualanData()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penjualan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penjualan.status', $status);
        }

        // Filter berdasarkan tipe filter
        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            // Filter berdasarkan rentang tanggal
            $builder->where('DATE(penjualan.tglpenjualan) >=', $tglAwal)
                ->where('DATE(penjualan.tglpenjualan) <=', $tglAkhir);
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(penjualan.tglpenjualan)', $bulan)
                ->where('YEAR(penjualan.tglpenjualan)', $tahun);
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            // Filter berdasarkan tahun
            $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);
        }

        $builder->orderBy('penjualan.tglpenjualan', 'DESC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($data as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPenjualanPdf()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');
        $kdpenjualan = $this->request->getGet('kdpenjualan'); // Tambahkan parameter kdpenjualan

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();
        $pelangganModel = new \App\Models\PelangganModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Jika ada parameter kdpenjualan, filter berdasarkan kode penjualan saja
        if (!empty($kdpenjualan)) {
            $builder->where('penjualan.kdpenjualan', $kdpenjualan);
        } else {
            // Filter berdasarkan pelanggan
            if (!empty($idpelanggan)) {
                $builder->where('penjualan.idpelanggan', $idpelanggan);
                $pelangganInfo = $pelangganModel->find($idpelanggan);
                $namaPelanggan = $pelangganInfo ? $pelangganInfo['nama'] : 'Pelanggan Umum';
            } else {
                $namaPelanggan = 'Semua';
            }

            // Filter berdasarkan status
            if ($status !== '' && $status !== null) {
                $builder->where('penjualan.status', $status);
            }

            // Filter berdasarkan tipe filter
            $filterText = '';
            if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
                // Filter berdasarkan rentang tanggal
                $builder->where('DATE(penjualan.tglpenjualan) >=', $tglAwal)
                    ->where('DATE(penjualan.tglpenjualan) <=', $tglAkhir);
                $filterText = 'Tanggal: ' . date('d-m-Y', strtotime($tglAwal)) . ' s/d ' . date('d-m-Y', strtotime($tglAkhir));
            } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
                // Filter berdasarkan bulan dan tahun
                $builder->where('MONTH(penjualan.tglpenjualan)', $bulan)
                    ->where('YEAR(penjualan.tglpenjualan)', $tahun);
                $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
                $filterText = 'Bulan: ' . $namaBulan . ' ' . $tahun;
            } elseif ($filterType == 'tahun' && !empty($tahun)) {
                // Filter berdasarkan tahun
                $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);
                $filterText = 'Tahun: ' . $tahun;
            } else {
                $filterText = 'Semua Data';
            }
        }

        $builder->orderBy('penjualan.tglpenjualan', 'DESC');
        $penjualan = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($penjualan as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Pending';
        } elseif ($status === '1') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Jika cetak per kode penjualan
        if (!empty($kdpenjualan)) {
            $filterText = 'Kode Penjualan: ' . $kdpenjualan;
            $namaPelanggan = !empty($penjualan) ? $penjualan[0]->namapelanggan : 'Tidak ditemukan';
            $statusText = !empty($penjualan) ? ($penjualan[0]->status == 0 ? 'Pending' : 'Selesai') : '';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Penjualan',
            'penjualan' => $penjualan,
            'filter' => [
                'type' => $filterType,
                'text' => $filterText,
                'pelanggan' => $namaPelanggan ?? 'Semua',
                'status' => $statusText,
                'is_detail' => !empty($kdpenjualan), // Tambahkan flag untuk menandai cetak detail
                'compact_view' => true, // Tambahkan flag untuk tampilan yang lebih ringkas
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/penjualan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Penjualan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function cetakDetailPenjualan($kdpenjualan)
    {
        return $this->cetakPenjualanPdf();
    }

    public function getPenjualanPerbulanData()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penjualan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penjualan.status', $status);
        }

        // Filter berdasarkan bulan dan tahun
        if (!empty($bulan) && !empty($tahun)) {
            $builder->where('MONTH(penjualan.tglpenjualan)', $bulan);
            $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);
        }

        $builder->orderBy('penjualan.tglpenjualan', 'ASC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($data as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPenjualanPerbulanPdf()
    {
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();
        $pelangganModel = new \App\Models\PelangganModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penjualan.idpelanggan', $idpelanggan);
            $pelangganInfo = $pelangganModel->find($idpelanggan);
            $namaPelanggan = $pelangganInfo ? $pelangganInfo['nama'] : 'Pelanggan Umum';
        } else {
            $namaPelanggan = 'Semua';
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penjualan.status', $status);
        }

        // Filter berdasarkan bulan dan tahun
        $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
        $filterText = 'Bulan: ' . $namaBulan . ' ' . $tahun;

        $builder->where('MONTH(penjualan.tglpenjualan)', $bulan);
        $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);

        $builder->orderBy('penjualan.tglpenjualan', 'ASC');
        $penjualan = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($penjualan as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Pending';
        } elseif ($status === '1') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Penjualan Bulan ' . $namaBulan . ' ' . $tahun,
            'penjualan' => $penjualan,
            'filter' => [
                'type' => 'bulan',
                'text' => $filterText,
                'pelanggan' => $namaPelanggan,
                'status' => $statusText,
                'is_detail' => false,
                'compact_view' => true,
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/penjualan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Penjualan_' . $namaBulan . '_' . $tahun . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function getPenjualanPertahunData()
    {
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penjualan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penjualan.status', $status);
        }

        // Filter berdasarkan tahun
        if (!empty($tahun)) {
            $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);
        }

        $builder->orderBy('penjualan.tglpenjualan', 'ASC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($data as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPenjualanPertahunPdf()
    {
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penjualanModel = new \App\Models\PenjualanModel();
        $detailPenjualanModel = new \App\Models\DetailPenjualanModel();
        $pelangganModel = new \App\Models\PelangganModel();

        $builder = $penjualanModel->builder();
        $builder->select('penjualan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penjualan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penjualan.idpelanggan', $idpelanggan);
            $pelangganInfo = $pelangganModel->find($idpelanggan);
            $namaPelanggan = $pelangganInfo ? $pelangganInfo['nama'] : 'Pelanggan Umum';
        } else {
            $namaPelanggan = 'Semua';
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penjualan.status', $status);
        }

        // Filter berdasarkan tahun
        $filterText = 'Tahun: ' . $tahun;
        $builder->where('YEAR(penjualan.tglpenjualan)', $tahun);

        $builder->orderBy('penjualan.tglpenjualan', 'ASC');
        $penjualan = $builder->get()->getResult();

        // Ambil detail untuk setiap penjualan
        foreach ($penjualan as &$item) {
            $item->detail = $detailPenjualanModel->getDetailWithBarang($item->kdpenjualan);
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Pending';
        } elseif ($status === '1') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Penjualan Tahun ' . $tahun,
            'penjualan' => $penjualan,
            'filter' => [
                'type' => 'tahun',
                'text' => $filterText,
                'pelanggan' => $namaPelanggan,
                'status' => $statusText,
                'is_detail' => false,
                'compact_view' => true,
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/penjualan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Penjualan_Tahun_' . $tahun . '.pdf', ['Attachment' => false]);
        exit();
    }

    // Laporan Penitipan
    public function penitipan()
    {
        $title = 'Laporan Penitipan';
        return view('admin/laporan/penitipan', compact('title'));
    }

    public function getPenitipanData()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');

        // Load model
        $penitipanModel = new \App\Models\PenitipanModel();
        $detailPenitipanModel = new \App\Models\DetailPenitipanModel();

        $builder = $this->db->table('penitipan');
        $builder->select('penitipan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->select('(SELECT h.namahewan FROM detailpenitipan dp JOIN hewan h ON h.idhewan = dp.idhewan WHERE dp.kdpenitipan = penitipan.kdpenitipan LIMIT 1) as namahewan', false);
        $builder->select('(SELECT h.idhewan FROM detailpenitipan dp JOIN hewan h ON h.idhewan = dp.idhewan WHERE dp.kdpenitipan = penitipan.kdpenitipan LIMIT 1) as kdhewan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penitipan.idpelanggan', 'left');

        // Filter berdasarkan pelanggan
        if (!empty($idpelanggan)) {
            $builder->where('penitipan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan status
        if ($status !== '' && $status !== null) {
            $builder->where('penitipan.status', $status);
        }

        // Filter berdasarkan tipe filter
        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            // Filter berdasarkan rentang tanggal
            $builder->where('DATE(penitipan.tglpenitipan) >=', $tglAwal)
                ->where('DATE(penitipan.tglpenitipan) <=', $tglAkhir);
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(penitipan.tglpenitipan)', $bulan)
                ->where('YEAR(penitipan.tglpenitipan)', $tahun);
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            // Filter berdasarkan tahun
            $builder->where('YEAR(penitipan.tglpenitipan)', $tahun);
        }

        $builder->orderBy('penitipan.tglpenitipan', 'DESC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap penitipan
        foreach ($data as &$item) {
            $item->detail = $detailPenitipanModel->getDetailWithInfo($item->kdpenitipan);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPenitipanPdf()
    {
        $filterType = $this->request->getGet('filter_type') ?? 'tanggal'; // tanggal, bulan, tahun
        $tglAwal = $this->request->getGet('tgl_awal');
        $tglAkhir = $this->request->getGet('tgl_akhir');
        $bulan = $this->request->getGet('bulan');
        $tahun = $this->request->getGet('tahun');
        $idpelanggan = $this->request->getGet('idpelanggan');
        $status = $this->request->getGet('status');
        $kdpenitipan = $this->request->getGet('kdpenitipan'); // Tambahkan parameter kdpenitipan

        // Load model
        $penitipanModel = new \App\Models\PenitipanModel();
        $detailPenitipanModel = new \App\Models\DetailPenitipanModel();
        $pelangganModel = new \App\Models\PelangganModel();

        $builder = $this->db->table('penitipan');
        $builder->select('penitipan.*, IFNULL(pelanggan.nama, "Pelanggan Umum") as namapelanggan', false);
        $builder->select('(SELECT h.namahewan FROM detailpenitipan dp JOIN hewan h ON h.idhewan = dp.idhewan WHERE dp.kdpenitipan = penitipan.kdpenitipan LIMIT 1) as namahewan', false);
        $builder->select('(SELECT h.idhewan FROM detailpenitipan dp JOIN hewan h ON h.idhewan = dp.idhewan WHERE dp.kdpenitipan = penitipan.kdpenitipan LIMIT 1) as kdhewan', false);
        $builder->join('pelanggan', 'pelanggan.idpelanggan = penitipan.idpelanggan', 'left');

        // Jika ada parameter kdpenitipan, filter berdasarkan kode penitipan saja
        if (!empty($kdpenitipan)) {
            $builder->where('penitipan.kdpenitipan', $kdpenitipan);
        } else {
            // Filter berdasarkan pelanggan
            if (!empty($idpelanggan)) {
                $builder->where('penitipan.idpelanggan', $idpelanggan);
                $pelangganInfo = $pelangganModel->find($idpelanggan);
                $namaPelanggan = $pelangganInfo ? $pelangganInfo['nama'] : 'Pelanggan Umum';
            } else {
                $namaPelanggan = 'Semua';
            }

            // Filter berdasarkan status
            if ($status !== '' && $status !== null) {
                $builder->where('penitipan.status', $status);
            }

            // Filter berdasarkan tipe filter
            $filterText = '';
            if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
                // Filter berdasarkan rentang tanggal
                $builder->where('DATE(penitipan.tglpenitipan) >=', $tglAwal)
                    ->where('DATE(penitipan.tglpenitipan) <=', $tglAkhir);
                $filterText = 'Tanggal: ' . date('d-m-Y', strtotime($tglAwal)) . ' s/d ' . date('d-m-Y', strtotime($tglAkhir));
            } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
                // Filter berdasarkan bulan dan tahun
                $builder->where('MONTH(penitipan.tglpenitipan)', $bulan)
                    ->where('YEAR(penitipan.tglpenitipan)', $tahun);
                $namaBulan = date('F', mktime(0, 0, 0, $bulan, 10));
                $filterText = 'Bulan: ' . $namaBulan . ' ' . $tahun;
            } elseif ($filterType == 'tahun' && !empty($tahun)) {
                // Filter berdasarkan tahun
                $builder->where('YEAR(penitipan.tglpenitipan)', $tahun);
                $filterText = 'Tahun: ' . $tahun;
            } else {
                $filterText = 'Semua Data';
            }
        }

        $builder->orderBy('penitipan.tglpenitipan', 'DESC');
        $penitipan = $builder->get()->getResult();

        // Ambil detail untuk setiap penitipan
        foreach ($penitipan as &$item) {
            $item->detail = $detailPenitipanModel->getDetailWithInfo($item->kdpenitipan);
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Pending';
        } elseif ($status === '1') {
            $statusText = 'Dalam Penitipan';
        } elseif ($status === '2') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Jika cetak per kode penitipan
        if (!empty($kdpenitipan)) {
            $filterText = 'Kode Penitipan: ' . $kdpenitipan;
            $namaPelanggan = !empty($penitipan) ? $penitipan[0]->namapelanggan : 'Tidak ditemukan';
            $statusText = !empty($penitipan) ? $penitipanModel->getStatusLabel($penitipan[0]->status) : '';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Penitipan',
            'penitipan' => $penitipan,
            'filter' => [
                'type' => $filterType,
                'text' => $filterText,
                'pelanggan' => $namaPelanggan ?? 'Semua',
                'status' => $statusText,
                'is_detail' => !empty($kdpenitipan), // Tambahkan flag untuk menandai cetak detail
                'compact_view' => true, // Tambahkan flag untuk tampilan yang lebih ringkas
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/penitipan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Penitipan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function cetakDetailPenitipan($kdpenitipan)
    {
        return $this->cetakPenitipanPdf();
    }

    // Laporan Perawatan
    public function perawatan()
    {
        $title = 'Laporan Perawatan';
        return view('admin/laporan/perawatan', compact('title'));
    }

    public function getPerawatanData()
    {
        $filterType = $this->request->getGet('filter_type') ?? '';
        $tglAwal = $this->request->getGet('tgl_awal') ?? '';
        $tglAkhir = $this->request->getGet('tgl_akhir') ?? '';
        $bulan = $this->request->getGet('bulan') ?? '';
        $tahun = $this->request->getGet('tahun') ?? '';
        $idpelanggan = $this->request->getGet('idpelanggan') ?? '';
        $status = $this->request->getGet('status') ?? '';
        $kdperawatan = $this->request->getGet('kdperawatan') ?? '';

        // Inisialisasi model
        $perawatanModel = new \App\Models\PerawatanModel();
        $detailPerawatanModel = new \App\Models\DetailPerawatanModel();

        // Query builder untuk mendapatkan data perawatan dengan join ke pelanggan dan hewan
        $builder = $this->db->table('perawatan');
        $builder->select('perawatan.*, perawatan.created_at, perawatan.updated_at, pelanggan.nama as namapelanggan, hewan.namahewan, hewan.idhewan as kdhewan');
        $builder->join('pelanggan', 'pelanggan.idpelanggan = perawatan.idpelanggan', 'left');
        $builder->join('hewan', 'hewan.idhewan = perawatan.idhewan', 'left');

        // Filter berdasarkan kode perawatan jika ada
        if (!empty($kdperawatan)) {
            $builder->where('perawatan.kdperawatan', $kdperawatan);
        }

        // Filter berdasarkan pelanggan jika ada
        if (!empty($idpelanggan)) {
            $builder->where('perawatan.idpelanggan', $idpelanggan);
        }

        // Filter berdasarkan status jika ada
        if ($status !== '' && $status !== null) {
            $builder->where('perawatan.status', $status);
        }

        // Filter berdasarkan tipe filter
        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            // Filter berdasarkan rentang tanggal
            $builder->where('DATE(perawatan.tglperawatan) >=', $tglAwal)
                ->where('DATE(perawatan.tglperawatan) <=', $tglAkhir);
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            // Filter berdasarkan bulan dan tahun
            $builder->where('MONTH(perawatan.tglperawatan)', $bulan)
                ->where('YEAR(perawatan.tglperawatan)', $tahun);
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            // Filter berdasarkan tahun
            $builder->where('YEAR(perawatan.tglperawatan)', $tahun);
        }

        $builder->orderBy('perawatan.tglperawatan', 'DESC');
        $data = $builder->get()->getResult();

        // Ambil detail untuk setiap perawatan
        foreach ($data as &$item) {
            $detailBuilder = $this->db->table('detailperawatan d');
            $detailBuilder->select('d.*, f.namafasilitas, f.kategori');
            $detailBuilder->join('fasilitas f', 'f.kdfasilitas = d.detailkdfasilitas', 'left');
            $detailBuilder->where('d.detailkdperawatan', $item->kdperawatan);
            $item->detail = $detailBuilder->get()->getResultArray();
        }

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakPerawatanPdf()
    {
        // Inisialisasi library DOMPDF
        $dompdf = new \Dompdf\Dompdf();
        $options = new \Dompdf\Options();
        $options->set('isRemoteEnabled', true);
        $dompdf->setOptions($options);

        // Ambil parameter filter
        $filterType = $this->request->getGet('filter_type') ?? '';
        $tglAwal = $this->request->getGet('tgl_awal') ?? '';
        $tglAkhir = $this->request->getGet('tgl_akhir') ?? '';
        $bulan = $this->request->getGet('bulan') ?? '';
        $tahun = $this->request->getGet('tahun') ?? '';
        $idpelanggan = $this->request->getGet('idpelanggan') ?? '';
        $status = $this->request->getGet('status') ?? '';
        $kdperawatan = $this->request->getGet('kdperawatan') ?? '';

        // Inisialisasi model
        $perawatanModel = new \App\Models\PerawatanModel();
        $detailPerawatanModel = new \App\Models\DetailPerawatanModel();

        // Siapkan teks filter
        $filterText = '';
        $namaPelanggan = 'Semua';

        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            $filterText = 'Tanggal: ' . date('d-m-Y', strtotime($tglAwal)) . ' s/d ' . date('d-m-Y', strtotime($tglAkhir));
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            $bulanNames = [
                '01' => 'Januari',
                '02' => 'Februari',
                '03' => 'Maret',
                '04' => 'April',
                '05' => 'Mei',
                '06' => 'Juni',
                '07' => 'Juli',
                '08' => 'Agustus',
                '09' => 'September',
                '10' => 'Oktober',
                '11' => 'November',
                '12' => 'Desember'
            ];
            $filterText = 'Bulan: ' . $bulanNames[$bulan] . ' ' . $tahun;
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            $filterText = 'Tahun: ' . $tahun;
        } else {
            $filterText = 'Semua Data';
        }

        // Query builder untuk mendapatkan data perawatan dengan join ke pelanggan dan hewan
        $builder = $this->db->table('perawatan');
        $builder->select('perawatan.*, perawatan.created_at, perawatan.updated_at, pelanggan.nama as namapelanggan, hewan.namahewan, hewan.idhewan as kdhewan');
        $builder->join('pelanggan', 'pelanggan.idpelanggan = perawatan.idpelanggan', 'left');
        $builder->join('hewan', 'hewan.idhewan = perawatan.idhewan', 'left');

        // Filter berdasarkan kode perawatan jika ada
        if (!empty($kdperawatan)) {
            $builder->where('perawatan.kdperawatan', $kdperawatan);
        }

        // Filter berdasarkan pelanggan jika ada
        if (!empty($idpelanggan)) {
            $builder->where('perawatan.idpelanggan', $idpelanggan);
            // Ambil nama pelanggan
            $pelangganData = $this->db->table('pelanggan')->select('nama')->where('idpelanggan', $idpelanggan)->get()->getRow();
            if ($pelangganData) {
                $namaPelanggan = $pelangganData->nama;
            }
        }

        // Filter berdasarkan status jika ada
        if ($status !== '' && $status !== null) {
            $builder->where('perawatan.status', $status);
        }

        // Filter berdasarkan tipe filter
        if ($filterType == 'tanggal' && !empty($tglAwal) && !empty($tglAkhir)) {
            $builder->where('DATE(perawatan.tglperawatan) >=', $tglAwal)
                ->where('DATE(perawatan.tglperawatan) <=', $tglAkhir);
        } elseif ($filterType == 'bulan' && !empty($bulan) && !empty($tahun)) {
            $builder->where('MONTH(perawatan.tglperawatan)', $bulan)
                ->where('YEAR(perawatan.tglperawatan)', $tahun);
        } elseif ($filterType == 'tahun' && !empty($tahun)) {
            $builder->where('YEAR(perawatan.tglperawatan)', $tahun);
        }

        $builder->orderBy('perawatan.tglperawatan', 'DESC');
        $perawatan = $builder->get()->getResult();

        // Ambil detail untuk setiap perawatan
        foreach ($perawatan as &$item) {
            $detailBuilder = $this->db->table('detailperawatan d');
            $detailBuilder->select('d.*, f.namafasilitas, f.kategori');
            $detailBuilder->join('fasilitas f', 'f.kdfasilitas = d.detailkdfasilitas', 'left');
            $detailBuilder->where('d.detailkdperawatan', $item->kdperawatan);
            $item->detail = $detailBuilder->get()->getResultArray();
        }

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        // Teks status
        $statusText = '';
        if ($status === '0') {
            $statusText = 'Menunggu';
        } elseif ($status === '1') {
            $statusText = 'Dalam Proses';
        } elseif ($status === '2') {
            $statusText = 'Selesai';
        } else {
            $statusText = 'Semua';
        }

        // Jika cetak per kode perawatan
        if (!empty($kdperawatan)) {
            $filterText = 'Kode Perawatan: ' . $kdperawatan;
            $namaPelanggan = !empty($perawatan) ? $perawatan[0]->namapelanggan : 'Tidak ditemukan';
            $statusText = !empty($perawatan) ? $perawatanModel->getStatusLabel($perawatan[0]->status) : '';
        }

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalCetak = date('d-m-Y H:i:s');
        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Perawatan',
            'perawatan' => $perawatan,
            'filter' => [
                'type' => $filterType,
                'text' => $filterText,
                'pelanggan' => $namaPelanggan ?? 'Semua',
                'status' => $statusText,
                'is_detail' => !empty($kdperawatan), // Tambahkan flag untuk menandai cetak detail
                'compact_view' => true, // Tambahkan flag untuk tampilan yang lebih ringkas
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'kota' => 'Kota Padang',
            'admin' => 'Admin Nana Cat Shop',
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/perawatan_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new \Dompdf\Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true); // Mengizinkan gambar dari URL

        // Inisialisasi DOMPDF
        $dompdf = new \Dompdf\Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Perawatan_' . date('Ymd_His') . '.pdf', ['Attachment' => false]);
        exit();
    }

    public function cetakDetailPerawatan($kdperawatan)
    {
        return $this->cetakPerawatanPdf();
    }

    public function getBarangMasukPertahunData()
    {
        $tahun = $this->request->getGet('tahun');

        if (empty($tahun)) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Parameter tahun diperlukan',
                'data' => []
            ]);
        }

        // Dapatkan semua detail barang masuk untuk tahun tertentu
        $builder = $this->db->table('detailbarangmasuk d');
        $builder->select('d.*, b.namabarang, b.satuan, b.hargajual, bm.tglmasuk, bm.kdspl, s.namaspl');
        $builder->join('barangmasuk bm', 'bm.kdmasuk = d.detailkdmasuk', 'left');
        $builder->join('barang b', 'b.kdbarang = d.detailkdbarang', 'left');
        $builder->join('supplier s', 's.kdspl = bm.kdspl', 'left');
        $builder->where('YEAR(bm.tglmasuk)', $tahun);
        $builder->orderBy('bm.tglmasuk', 'ASC');
        $builder->orderBy('d.detailkdbarang', 'ASC');

        $data = $builder->get()->getResultArray();

        return $this->response->setJSON([
            'status' => 'success',
            'data' => $data
        ]);
    }

    public function cetakBarangMasukPertahunPdf()
    {
        $tahun = $this->request->getGet('tahun');

        if (empty($tahun)) {
            return redirect()->back()->with('error', 'Parameter tahun diperlukan');
        }

        // Dapatkan semua detail barang masuk untuk tahun tertentu
        $builder = $this->db->table('detailbarangmasuk d');
        $builder->select('d.*, b.namabarang, b.satuan, b.hargajual, bm.tglmasuk, bm.kdspl, s.namaspl');
        $builder->join('barangmasuk bm', 'bm.kdmasuk = d.detailkdmasuk', 'left');
        $builder->join('barang b', 'b.kdbarang = d.detailkdbarang', 'left');
        $builder->join('supplier s', 's.kdspl = bm.kdspl', 'left');
        $builder->where('YEAR(bm.tglmasuk)', $tahun);
        $builder->orderBy('bm.tglmasuk', 'ASC');
        $builder->orderBy('d.detailkdbarang', 'ASC');

        $barangMasuk = $builder->get()->getResultArray();

        // Siapkan data untuk view
        $logoPath = FCPATH . 'assets/img/catshoplogo.png';
        $logoData = '';

        if (file_exists($logoPath)) {
            $logoType = pathinfo($logoPath, PATHINFO_EXTENSION);
            $logoData = 'data:image/' . $logoType . ';base64,' . base64_encode(file_get_contents($logoPath));
        }

        $tanggalCetak = date('d-m-Y H:i:s');

        // Format tanggal Indonesia
        $bulanIndo = [
            'January' => 'Januari',
            'February' => 'Februari',
            'March' => 'Maret',
            'April' => 'April',
            'May' => 'Mei',
            'June' => 'Juni',
            'July' => 'Juli',
            'August' => 'Agustus',
            'September' => 'September',
            'October' => 'Oktober',
            'November' => 'November',
            'December' => 'Desember'
        ];

        $tanggalTTD = date('d F Y');
        $tanggalTTD = str_replace(array_keys($bulanIndo), array_values($bulanIndo), $tanggalTTD);

        $data = [
            'title' => 'Laporan Barang Masuk Pertahun',
            'barangMasuk' => $barangMasuk,
            'filter' => [
                'tahun' => $tahun,
            ],
            'tanggal_cetak' => $tanggalCetak,
            'tanggal_ttd' => $tanggalTTD,
            'logo' => $logoData
        ];

        // Render view ke HTML
        $html = view('admin/laporan/barang_masuk_pertahun_pdf', $data);

        // Konfigurasi DOMPDF
        $options = new Options();
        $options->set('isHtml5ParserEnabled', true);
        $options->set('isPhpEnabled', true);
        $options->set('isRemoteEnabled', true);

        // Inisialisasi DOMPDF
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'landscape');
        $dompdf->render();

        // Output PDF
        $dompdf->stream('Laporan_Barang_Masuk_Tahun_' . $tahun . '.pdf', ['Attachment' => false]);
        exit();
    }
}
