<?php

namespace App\Livewire\Admin\Dashboard;

use App\Models\KategoriSpp;
use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\User;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

class Index extends Component
{
    public function render()
    {
        /** @disregard P1005 */
        $totalSiswa     = Siswa::count();
        /** @disregard P1005 */
        $totalWali      = User::where('role', 'wali_murid')->count();
        /** @disregard P1005 */
        $totalTagihan   = Tagihan::count();
        /** @disregard P1005 */
        $tagLunas       = Tagihan::where('status_tagihan', 'Lunas')->count();
        /** @disregard P1005 */
        $tagBelum       = Tagihan::where('status_tagihan', 'Belum Lunas')->count();

        /** @disregard P1005 */
        $terkumpul = Pembayaran::whereIn('status_pembayaran', ['settlement', 'capture', 'Lunas'])
            ->sum('jumlah_bayar');

        // Potensi total (semua tagihan × nominal kategori) dihitung langsung via SQL join
        $potensi = Tagihan::join('kategori_spps', 'tagihans.id_kategori', '=', 'kategori_spps.id_kategori')
            ->sum('kategori_spps.nominal_spp');

        // Tagihan belum lunas terbaru (5 data)
        $tagihanMenunggu = Tagihan::with(['siswa', 'kategori_spp'])
            ->where('status_tagihan', 'Belum Lunas')
            ->latest()
            ->take(5)
            ->get();

        // Pembayaran terbaru (5 data)
        $pembayaranTerbaru = Pembayaran::with(['tagihan.siswa'])
            ->latest()
            ->take(5)
            ->get();

        // Rekapitulasi per bulan (12 bulan terakhir) - 1 query SQL teragregasi
        $bulanList = [
            'Januari',
            'Februari',
            'Maret',
            'April',
            'Mei',
            'Juni',
            'Juli',
            'Agustus',
            'September',
            'Oktober',
            'November',
            'Desember'
        ];

        $tagihanPerBulan = Tagihan::where('tahun', now()->year)
            ->select('bulan', 'status_tagihan', DB::raw('count(*) as total'))
            ->groupBy('bulan', 'status_tagihan')
            ->get();

        $rekapBulan = collect($bulanList)->map(function ($bulan) use ($tagihanPerBulan) {
            $lunas = $tagihanPerBulan->first(fn($item) => $item->bulan === $bulan && $item->status_tagihan === 'Lunas')?->total ?? 0;
            $belum = $tagihanPerBulan->first(fn($item) => $item->bulan === $bulan && $item->status_tagihan === 'Belum Lunas')?->total ?? 0;

            return [
                'bulan'  => $bulan,
                'lunas'  => $lunas,
                'belum'  => $belum,
            ];
        });

        // Top 5 santri dengan tagihan belum lunas terbanyak
        $santriTunggakan = Siswa::withCount([
            'tagihan as belum_lunas_count' => fn($q) => $q->where('status_tagihan', 'Belum Lunas')
        ])->having('belum_lunas_count', '>', 0)
            ->orderByDesc('belum_lunas_count')
            ->take(5)
            ->get();

        /** @disregard P1005 */
        $kategoris = KategoriSpp::latest()->take(5)->get();

        $persen = $potensi > 0 ? round(($terkumpul / $potensi) * 100, 1) : 0;

        return view('livewire.admin.dashboard.index', compact(
            'totalSiswa',
            'totalWali',
            'totalTagihan',
            'tagLunas',
            'tagBelum',
            'terkumpul',
            'potensi',
            'persen',
            'tagihanMenunggu',
            'pembayaranTerbaru',
            'rekapBulan',
            'santriTunggakan',
            'kategoris',
            'bulanList'
        ));
    }
}
