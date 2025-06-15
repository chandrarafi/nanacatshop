<?php

namespace App\Controllers;

use App\Models\PelangganModel;
use App\Models\HewanModel;
use App\Models\BarangModel;
use App\Models\KategoriModel;
use App\Models\FasilitasModel;
use App\Models\SupplierModel;
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

    public function __construct()
    {
        $this->pelangganModel = new PelangganModel();
        $this->hewanModel = new HewanModel();
        $this->barangModel = new BarangModel();
        $this->kategoriModel = new KategoriModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->supplierModel = new SupplierModel();
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
            'tanggal_cetak' => date('d-m-Y H:i:s'),
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
            'tanggal_cetak' => date('d-m-Y H:i:s'),
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
            'tanggal_cetak' => date('d-m-Y H:i:s'),
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
            'tanggal_cetak' => date('d-m-Y H:i:s'),
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
            'tanggal_cetak' => date('d-m-Y H:i:s'),
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
}
