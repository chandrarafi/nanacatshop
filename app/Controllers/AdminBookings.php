<?php

namespace App\Controllers;

use App\Models\BookingModel;

class AdminBookings extends BaseController
{
    protected $bookingModel;

    public function __construct()
    {
        $this->bookingModel = new BookingModel();
        helper('format');
    }

    public function index()
    {
        $bookings = $this->bookingModel->orderBy('created_at', 'DESC')->findAll();
        return view('admin/bookings/index', [
            'title' => 'Kelola Booking',
            'bookings' => $bookings
        ]);
    }

    public function show($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            throw \CodeIgniter\Exceptions\PageNotFoundException::forPageNotFound('Booking tidak ditemukan');
        }
        // Build itemized services from stored service_name
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
        $pelanggan = null;
        if (!empty($booking['user_id'])) {
            $pelanggan = (new \App\Models\PelangganModel())->where('user_id', $booking['user_id'])->first();
        }
        return view('admin/bookings/show', [
            'title' => 'Detail Booking',
            'booking' => $booking,
            'items' => $items,
            'pelanggan' => $pelanggan,
        ]);
    }

    public function invoice($id)
    {
        $booking = $this->bookingModel->find($id);
        if (!$booking) {
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
        $pelanggan = null;
        if (!empty($booking['user_id'])) {
            $pelanggan = (new \App\Models\PelangganModel())->where('user_id', $booking['user_id'])->first();
        }
        return view('admin/bookings/invoice', [
            'title' => 'Invoice Booking',
            'booking' => $booking,
            'items' => $items,
            'pelanggan' => $pelanggan,
        ]);
    }

    public function updateStatus($id)
    {
        $status = $this->request->getPost('status');
        if (!in_array($status, ['pending', 'confirmed', 'completed', 'cancelled'])) {
            return redirect()->back()->with('error', 'Status tidak valid');
        }

        $booking = $this->bookingModel->find($id);
        if (!$booking) {
            return redirect()->back()->with('error', 'Booking tidak ditemukan');
        }

        $updates = ['status' => $status];

        // Require pelunasan if completing and current payment is DP
        if ($status === 'completed' && ($booking['payment_type'] ?? 'dp') === 'dp') {
            $markLunas = (bool) $this->request->getPost('mark_lunas');
            $file = $this->request->getFile('payment_proof');
            $hasValidFile = $file && $file->isValid() && !$file->hasMoved();

            if (!$markLunas && !$hasValidFile) {
                return redirect()->back()->with('error', 'Pelunasan diperlukan untuk menyelesaikan booking (unggah bukti atau centang sudah lunas).');
            }

            $updates['payment_type'] = 'lunas';
            $paymentBank = $this->request->getPost('payment_bank');
            if (!empty($paymentBank)) {
                $updates['payment_bank'] = $paymentBank;
            }
            if ($hasValidFile) {
                $newName = $file->getRandomName();
                $uploadDir = FCPATH . 'uploads/payment_proofs';
                if (!is_dir($uploadDir)) {
                    @mkdir($uploadDir, 0755, true);
                }
                if ($file->move($uploadDir, $newName)) {
                    $updates['payment_proof'] = 'uploads/payment_proofs/' . $newName;
                }
            }
        }

        $this->bookingModel->update($id, $updates);
        return redirect()->to(site_url('admin/bookings/' . $id))->with('success', 'Status berhasil diperbarui');
    }
}
