<?php

namespace App\Controllers;

use App\Models\BarangModel;
use App\Models\FasilitasModel;
use App\Models\KategoriModel;

class LandingPage extends BaseController
{
    protected $barangModel;
    protected $fasilitasModel;
    protected $kategoriModel;

    public function __construct()
    {
        $this->barangModel = new BarangModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->kategoriModel = new KategoriModel();
    }

    public function index()
    {
        $featured = $this->barangModel
            ->where('jumlah >', 0)
            ->orderBy('kdbarang', 'DESC')
            ->findAll(4);

        $data = [
            'title' => 'Nana Cat Shop - Toko dan Layanan Perawatan Kucing',
            'barang' => $this->barangModel->findAll(),
            'kategori' => $this->kategoriModel->findAll(),
            'kategori_filter' => $this->kategoriModel->getKategoriFilter(),
            'layanan_perawatan' => $this->fasilitasModel->getWithKategoriPerawatan(),
            'layanan_penitipan' => $this->fasilitasModel->getWithKategori(),
            'featured' => $featured
        ];

        return view('landing_page', $data);
    }
}
