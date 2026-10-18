<?php

namespace App\Livewire\KepalaSekolah\Dashboard;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\KategoriSpp;
use Illuminate\Support\Facades\DB;
use Livewire\Component;

/** @disregard P1005 */
class Index extends Component
{

    public function render()
    {

        $now        = now();
        $bulanIni   = $now->locale('id')->isoFormat('MMMM');
        $tahunIni   = $now->year;
        $bulanLalu  = $now->copy()->subMonth()->locale('id')->isoFormat('MMMM');
        $tahunLalu  = $now->copy()->subMonth()->year;

        /** @disregard P1005 */
        $totalSiswa    = Siswa::count();
        /** @disregard P1005 */
        $totalTagihan  = Tagihan::count();
        /** @disregard P1005 */
        $totalLunas    = Tagihan::where('status_tagihan', 'Lunas')->count();
        /** @disregard P1005 */
        $totalBelum    = Tagihan::where('status_tagihan', 'Belum Lunas')->count();
        /** @disregard P1005 */
        $totalPemasukan = Pembayaran::where('status_pembayaran', 'settlement')->sum('jumlah_bayar');
        /** @disregard P1005 */
        $pemasukanBulanIni = Pembayaran::where('status_pembayaran', 'settlement')
            ->whereHas('tagihan', fn($q) => $q->where('bulan', $bulanIni)->where('tahun', $tahunIni))
            ->sum('jumlah_bayar');
        /** @disregard P1005 */
        $pemasukanBulanLalu = Pembayaran::where('status_pembayaran', 'settlement')
            ->whereHas('tagihan', fn($q) => $q->where('bulan', $bulanLalu)->where('tahun', $tahunLalu))
            ->sum('jumlah_bayar');

        $growthPersen = $pemasukanBulanLalu > 0
            ? round((($pemasukanBulanIni - $pemasukanBulanLalu) / $pemasukanBulanLalu) * 100, 1)
            : null;

        /** @disregard P1005 */
        $tunggakan = Tagihan::where('status_tagihan', 'Belum Lunas')
            ->join('kategori_spps', 'tagihans.id_kategori', '=', 'kategori_spps.id_kategori')
            ->sum('kategori_spps.nominal_spp');

        // ── Pemasukan 6 bulan terakhir (grafik - 1 query agregasi) ──
        $listPeriode = collect();
        for ($i = 5; $i >= 0; $i--) {
            $tgl = $now->copy()->subMonths($i);
            $listPeriode->push([
                'label' => $tgl->isoFormat('MMM'),
                'bulan' => $tgl->locale('id')->isoFormat('MMMM'),
                'tahun' => $tgl->year,
            ]);
        }

        $pembayaran6Bulan = Pembayaran::where('status_pembayaran', 'settlement')
            ->join('tagihans', 'pembayarans.id_tagihan', '=', 'tagihans.id_tagihan')
            ->where(function ($query) use ($listPeriode) {
                foreach ($listPeriode as $p) {
                    $query->orWhere(function ($q) use ($p) {
                        $q->where('tagihans.bulan', $p['bulan'])->where('tagihans.tahun', $p['tahun']);
                    });
                }
            })
            ->select('tagihans.bulan', 'tagihans.tahun', DB::raw('sum(pembayarans.jumlah_bayar) as total'))
            ->groupBy('tagihans.bulan', 'tagihans.tahun')
            ->get();

        $chartData = $listPeriode->map(function ($p) use ($pembayaran6Bulan) {
            $match = $pembayaran6Bulan->first(fn($item) => $item->bulan === $p['bulan'] && (int)$item->tahun === (int)$p['tahun']);
            return [
                'label'   => $p['label'],
                'nominal' => (int) ($match?->total ?? 0),
            ];
        });

        // ── Persentase lunas per kelas (1 query agregasi) ─────
        $rekapPerKelas = Tagihan::join('siswas', 'tagihans.id_siswa', '=', 'siswas.id_siswa')
            ->select(
                'siswas.kelas',
                DB::raw("COUNT(*) as total_tagihan"),
                DB::raw("SUM(CASE WHEN tagihans.status_tagihan = 'Lunas' THEN 1 ELSE 0 END) as total_lunas")
            )
            ->groupBy('siswas.kelas')
            ->get()
            ->keyBy('kelas');

        $perKelas = Siswa::select('kelas', DB::raw('count(*) as total'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->map(function ($row) use ($rekapPerKelas) {
                $stat = $rekapPerKelas->get($row->kelas);
                $lunas = (int) ($stat?->total_lunas ?? 0);
                $total = (int) ($stat?->total_tagihan ?? 0);

                return [
                    'kelas'   => $row->kelas,
                    'siswa'   => $row->total,
                    'lunas'   => $lunas,
                    'total'   => $total,
                    'persen'  => $total > 0 ? round(($lunas / $total) * 100) : 0,
                ];
            });

        // ── Transaksi terbaru ──────────────────────────────────
        $transaksiTerbaru = Pembayaran::with(['tagihan.siswa', 'tagihan.kategori_spp'])
            ->where('status_pembayaran', 'settlement')
            ->latest('waktu_pembayaran')
            ->limit(6)
            ->get();

        // ── Kategori SPP aktif ─────────────────────────────────
        $kategoris = KategoriSpp::withCount('tagihan')->latest()->limit(4)->get();

        return view('livewire.kepala-sekolah.dashboard.index', compact(
            'totalSiswa',
            'totalTagihan',
            'totalLunas',
            'totalBelum',
            'totalPemasukan',
            'pemasukanBulanIni',
            'growthPersen',
            'tunggakan',
            'chartData',
            'perKelas',
            'transaksiTerbaru',
            'kategoris',
            'bulanIni',
            'tahunIni',
        ));
    }
}
