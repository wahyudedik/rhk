<?php

namespace App\Http\Controllers;

use App\Models\Laporan;
use App\Models\Rhk;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PelangganDashboardController extends Controller
{
    public function index(Request $request): View
    {
        $user = $request->user();
        $sub = $user->activeSubscription();

        // Stats dasar
        $totalLaporan = Laporan::where('user_id', $user->id)->count();
        $laporanBulanIni = Laporan::where('user_id', $user->id)
            ->where('bulan', now()->translatedFormat('F'))
            ->where('tahun', now()->year)
            ->count();
        $laporanTahunIni = Laporan::where('user_id', $user->id)
            ->where('tahun', now()->year)
            ->count();

        // Laporan per bulan (12 bulan terakhir untuk chart)
        $bulanList = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];
        $laporanPerBulan = Laporan::where('user_id', $user->id)
            ->where('tahun', now()->year)
            ->selectRaw('bulan, COUNT(*) as total')
            ->groupBy('bulan')
            ->pluck('total', 'bulan')
            ->toArray();

        $chartData = array_map(fn ($b) => $laporanPerBulan[$b] ?? 0, $bulanList);

        // Laporan per RHK
        $laporanPerRhk = Laporan::where('user_id', $user->id)
            ->join('rhks', 'laporans.rhk_id', '=', 'rhks.id')
            ->selectRaw('rhks.nama, COUNT(*) as total')
            ->groupBy('rhks.id', 'rhks.nama')
            ->orderByDesc('total')
            ->limit(5)
            ->get();

        // Laporan terbaru
        $laporanTerbaru = Laporan::with(['rhk', 'jenisRhk'])
            ->where('user_id', $user->id)
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        // Info langganan
        $sisaLaporan = $sub ? $sub->sisaLaporan() : 0;
        $batasLaporan = $sub ? $sub->billingPlan->batas_laporan_per_bulan : 0;
        $isTrialPlan = $sub && $sub->billingPlan->is_trial;
        $sisaHari = $sub ? (int) now()->diffInDays($sub->berakhir_at) : 0;

        return view('pelanggan.dashboard', compact(
            'user', 'sub',
            'totalLaporan', 'laporanBulanIni', 'laporanTahunIni',
            'bulanList', 'chartData',
            'laporanPerRhk', 'laporanTerbaru',
            'sisaLaporan', 'batasLaporan', 'isTrialPlan', 'sisaHari'
        ));
    }
}
