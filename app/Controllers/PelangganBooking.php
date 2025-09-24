<?php

namespace App\Controllers;

use App\Models\BookingModel;
use App\Models\FasilitasModel;
use Config\BookingConfig;

class PelangganBooking extends BaseController
{
    protected $bookingModel;
    protected $fasilitasModel;
    protected $bookingConfig;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        $this->fasilitasModel = new FasilitasModel();
        $this->bookingConfig = config('BookingConfig');
        helper('format');
        helper('form');
    }

    public function form()
    {
        $user = session()->get();
        $services = $this->fasilitasModel->getWithKategoriPerawatan();
        return view('pelanggan/booking_form', [
            'title' => 'Booking Perawatan',
            'user' => $user,
            'services' => $services,
            'timeSlots' => $this->bookingConfig->timeSlots
        ]);
    }

    public function store()
    {
        if (!$this->request->is('post')) {
            return redirect()->back();
        }

        $userId = (int) session()->get('user_id');
        $serviceIdRaw = $this->request->getPost('service_id');
        $serviceName = $this->request->getPost('service_name');
        $price = (int) $this->request->getPost('price');
        // support multiple ids like "FS...,GM..." or numeric; keep numeric id if available otherwise 0
        $serviceId = 0;
        if ($serviceIdRaw) {
            $parts = array_filter(array_map('trim', explode(',', $serviceIdRaw)));
            $first = $parts[0] ?? '';
            if (is_numeric($first)) {
                $serviceId = (int) $first;
            } else {
                $serviceId = 0; // fallback when using string IDs like kdfasilitas
            }
        }
        $bookingDate = $this->request->getPost('booking_date');
        $bookingTime = $this->request->getPost('booking_time');
        $notes = $this->request->getPost('notes');
        $paymentType = $this->request->getPost('payment_type') ?: 'dp';

        // Handle payment proof upload (optional)
        $paymentProofFilename = null;
        $file = $this->request->getFile('payment_proof');
        if ($file && $file->isValid() && !$file->hasMoved()) {
            $newName = $file->getRandomName();
            $uploadDir = FCPATH . 'uploads/payment_proofs';
            if (!is_dir($uploadDir)) {
                @mkdir($uploadDir, 0755, true);
            }
            if ($file->move($uploadDir, $newName)) {
                $paymentProofFilename = 'uploads/payment_proofs/' . $newName;
            }
        }

        // Validate slot capacity (max bookings per date/time)
        $maxPerSlot = (int) env('booking.maxPerSlot', $this->bookingConfig->maxPerSlot);
        $existing = $this->bookingModel->where('booking_date', $bookingDate)
            ->where('booking_time', $bookingTime)
            ->whereIn('status', ['pending', 'confirmed'])
            ->countAllResults();
        if ($existing >= $maxPerSlot) {
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Kuota pada jam tersebut sudah penuh. Silakan pilih waktu lain.'
            ]);
        }

        $data = [
            'user_id' => $userId,
            'service_id' => $serviceId,
            'service_name' => $serviceName,
            'price' => $price,
            'booking_date' => $bookingDate,
            'booking_time' => $bookingTime,
            'status' => 'pending',
            'notes' => $notes,
            'payment_type' => $paymentType,
            'payment_proof' => $paymentProofFilename,
        ];

        if (!$this->bookingModel->insert($data)) {
            // on error, cleanup uploaded proof
            if ($paymentProofFilename && file_exists(FCPATH . $paymentProofFilename)) {
                @unlink(FCPATH . $paymentProofFilename);
            }
            return $this->response->setJSON([
                'status' => 'error',
                'message' => 'Gagal membuat booking',
                'errors' => $this->bookingModel->errors()
            ]);
        }

        return $this->response->setJSON([
            'status' => 'success',
            'message' => 'Booking berhasil dibuat',
            'redirect' => site_url('pelanggan/bookings')
        ]);
    }

    public function index()
    {
        $userId = (int) session()->get('user_id');
        $bookings = $this->bookingModel->getByUser($userId);
        return view('pelanggan/bookings', [
            'title' => 'Booking Saya',
            'user' => session()->get(),
            'bookings' => $bookings
        ]);
    }

    public function availability()
    {
        $date = $this->request->getGet('date');
        if (!$date) {
            return $this->response->setJSON(['status' => 'error', 'message' => 'Tanggal wajib diisi']);
        }
        $maxPerSlot = (int) env('booking.maxPerSlot', $this->bookingConfig->maxPerSlot);
        $result = [];
        foreach ($this->bookingConfig->timeSlots as $slot) {
            $count = $this->bookingModel->where('booking_date', $date)
                ->where('booking_time', $slot)
                ->whereIn('status', ['pending', 'confirmed'])
                ->countAllResults();
            $result[$slot] = [
                'count' => $count,
                'remaining' => max(0, $maxPerSlot - $count),
                'full' => $count >= $maxPerSlot
            ];
        }
        return $this->response->setJSON(['status' => 'success', 'date' => $date, 'slots' => $result]);
    }

    public function invoice($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking || (int) $booking['user_id'] !== (int) session()->get('user_id')) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Booking tidak ditemukan');
        }
        $items = [];
        $names = array_filter(array_map('trim', explode(',', (string) ($booking['service_name'] ?? ''))));
        if (!empty($names)) {
            $fasilitasModel = new \App\Models\FasilitasModel();
            foreach ($names as $nm) {
                $fac = $fasilitasModel->where('namafasilitas', $nm)->first();
                $items[] = [
                    'name' => $nm,
                    'price' => $fac['harga'] ?? null,
                ];
            }
        }
        return view('pelanggan/booking_invoice', [
            'title' => 'Invoice Booking',
            'booking' => $booking,
            'user' => session()->get(),
            'items' => $items,
        ]);
    }
}
