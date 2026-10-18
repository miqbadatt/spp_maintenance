<?php

namespace App\Livewire\KepalaSekolah\Reports;

use App\Models\Pembayaran;
use App\Models\Siswa;
use App\Models\Tagihan;
use App\Models\KategoriSpp;
use Illuminate\Support\Facades\DB;
use Livewire\Component;
use Livewire\WithPagination;

class Index extends Component
{
    use WithPagination;

    public string $filterBulan     = '';
    public string $filterTahun     = '';
    public string $filterKelas     = '';
    public string $filterKategori  = '';
    public string $filterStatus    = '';

    public function updatingFilterBulan(): void
    {
        $this->resetPage();
    }
    public function updatingFilterTahun(): void
    {
        $this->resetPage();
    }
    public function updatingFilterKelas(): void
    {
        $this->resetPage();
    }
    public function updatingFilterKategori(): void
    {
        $this->resetPage();
    }
    public function updatingFilterStatus(): void
    {
        $this->resetPage();
    }


    public function render()
    {
        $now      = now();
        $bulanIni = $this->filterBulan ?: $now->locale('id')->isoFormat('MMMM');
        $tahunIni = $this->filterTahun ?: $now->year;

        // ── Ringkasan periode yang dipilih ─────────────────────
        $baseTagihan = Tagihan::query()
            ->when($this->filterBulan,    fn($q) => $q->where('bulan', $this->filterBulan))
            ->when($this->filterTahun,    fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterKategori, fn($q) => $q->where('id_kategori', $this->filterKategori))
            ->when($this->filterKelas,    fn($q) => $q->whereHas('siswa', fn($q2) => $q2->where('kelas', $this->filterKelas)));

        $totalTagihan  = (clone $baseTagihan)->count();
        $totalLunas    = (clone $baseTagihan)->where('status_tagihan', 'Lunas')->count();
        $totalBelum    = (clone $baseTagihan)->where('status_tagihan', 'Belum Lunas')->count();
        $persen        = $totalTagihan > 0 ? round(($totalLunas / $totalTagihan) * 100) : 0;
        /** @disregard P1005 */
        $totalPemasukan = Pembayaran::where('status_pembayaran', 'settlement')
            ->whereHas('tagihan', function ($q) {
                $q->when($this->filterBulan,    fn($q2) => $q2->where('bulan', $this->filterBulan))
                    ->when($this->filterTahun,    fn($q2) => $q2->where('tahun', $this->filterTahun))
                    ->when($this->filterKategori, fn($q2) => $q2->where('id_kategori', $this->filterKategori))
                    ->when($this->filterKelas,    fn($q2) => $q2->whereHas('siswa', fn($q3) => $q3->where('kelas', $this->filterKelas)));
            })
            ->sum('jumlah_bayar');

        $totalTunggakan = (clone $baseTagihan)
            ->where('status_tagihan', 'Belum Lunas')
            ->join('kategori_spps', 'tagihans.id_kategori', '=', 'kategori_spps.id_kategori')
            ->sum('kategori_spps.nominal_spp');

        // ── Rekap per kelas (Teragregasi dalam 2 query cepat, tanpa N+1) ──
        $statsTagihanPerKelas = Tagihan::join('siswas', 'tagihans.id_siswa', '=', 'siswas.id_siswa')
            ->when($this->filterBulan,    fn($q) => $q->where('tagihans.bulan', $this->filterBulan))
            ->when($this->filterTahun,    fn($q) => $q->where('tagihans.tahun', $this->filterTahun))
            ->when($this->filterKategori, fn($q) => $q->where('tagihans.id_kategori', $this->filterKategori))
            ->select(
                'siswas.kelas',
                DB::raw("COUNT(*) as total_tagihan"),
                DB::raw("SUM(CASE WHEN tagihans.status_tagihan = 'Lunas' THEN 1 ELSE 0 END) as total_lunas")
            )
            ->groupBy('siswas.kelas')
            ->get()
            ->keyBy('kelas');

        $statsBayarPerKelas = Pembayaran::where('status_pembayaran', 'settlement')
            ->join('tagihans', 'pembayarans.id_tagihan', '=', 'tagihans.id_tagihan')
            ->join('siswas', 'tagihans.id_siswa', '=', 'siswas.id_siswa')
            ->when($this->filterBulan,    fn($q) => $q->where('tagihans.bulan', $this->filterBulan))
            ->when($this->filterTahun,    fn($q) => $q->where('tagihans.tahun', $this->filterTahun))
            ->when($this->filterKategori, fn($q) => $q->where('tagihans.id_kategori', $this->filterKategori))
            ->select('siswas.kelas', DB::raw('SUM(pembayarans.jumlah_bayar) as total_nominal'))
            ->groupBy('siswas.kelas')
            ->get()
            ->keyBy('kelas');

        $rekapKelas = Siswa::select('kelas', DB::raw('count(*) as jumlah_siswa'))
            ->groupBy('kelas')
            ->orderBy('kelas')
            ->get()
            ->map(function ($row) use ($statsTagihanPerKelas, $statsBayarPerKelas) {
                $statT = $statsTagihanPerKelas->get($row->kelas);
                $statB = $statsBayarPerKelas->get($row->kelas);

                $lunas   = (int) ($statT?->total_lunas ?? 0);
                $total   = (int) ($statT?->total_tagihan ?? 0);
                $nominal = (int) ($statB?->total_nominal ?? 0);

                return [
                    'kelas'       => $row->kelas,
                    'siswa'       => $row->jumlah_siswa,
                    'lunas'       => $lunas,
                    'belum_lunas' => $total - $lunas,
                    'persen'      => $total > 0 ? round(($lunas / $total) * 100) : 0,
                    'nominal'     => $nominal,
                ];
            });

        // ── Detail tagihan (tabel paginasi) ────────────────────
        $tagihans = Tagihan::with(['siswa', 'kategori_spp', 'pembayaran'])
            ->when($this->filterBulan,    fn($q) => $q->where('bulan', $this->filterBulan))
            ->when($this->filterTahun,    fn($q) => $q->where('tahun', $this->filterTahun))
            ->when($this->filterStatus,   fn($q) => $q->where('status_tagihan', $this->filterStatus))
            ->when($this->filterKategori, fn($q) => $q->where('id_kategori', $this->filterKategori))
            ->when($this->filterKelas,    fn($q) => $q->whereHas('siswa', fn($q2) => $q2->where('kelas', $this->filterKelas)))
            ->latest()
            ->paginate(15);

        // ── Support data ───────────────────────────────────────
        $kategoris = KategoriSpp::all();
        $kelasList = Siswa::select('kelas')->distinct()->orderBy('kelas')->pluck('kelas');
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
            'Desember',
        ];

        return view('livewire.kepala-sekolah.reports.index', compact(
            'totalTagihan',
            'totalLunas',
            'totalBelum',
            'persen',
            'totalPemasukan',
            'totalTunggakan',
            'rekapKelas',
            'tagihans',
            'kategoris',
            'kelasList',
            'bulanList',
        ));
    }
}
