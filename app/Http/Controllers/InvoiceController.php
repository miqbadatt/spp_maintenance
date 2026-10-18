<?php

namespace App\Http\Controllers;

use App\Models\Pembayaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class InvoiceController extends Controller
{
    public function show($id_pembayaran)
    {
        // Ambil data pembayaran beserta relasinya
        $pembayaran = Pembayaran::with(['tagihan.siswa', 'tagihan.kategori_spp'])
            ->where('id_pembayaran', $id_pembayaran)
            ->firstOrFail();

        // Pastikan pembayaran ini sudah lunas (settlement)
        if ($pembayaran->status_pembayaran !== 'settlement') {
            abort(403, 'Invoice hanya tersedia untuk pembayaran yang sudah lunas.');
        }

        // Pastikan wali murid hanya dapat mengakses invoice santri miliknya sendiri
        $user = Auth::user();
        if ($user && $user->role === 'wali_murid' && $pembayaran->tagihan?->siswa?->id_user !== $user->id) {
            abort(403, 'Anda tidak memiliki izin untuk melihat invoice ini.');
        }

        return view('wali-murid.invoice', compact('pembayaran'));
    }
}
