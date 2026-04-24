<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Models\Laporan;
use App\Models\Rhk;
use App\Models\User;
use Illuminate\View\View;

class DashboardController extends Controller
{
    public function index(): View
    {
        $stats = [
            'total_pelanggan' => User::where('role', Role::Pelanggan)->count(),
            'total_laporan' => Laporan::count(),
            'total_rhk' => Rhk::count(),
            'laporan_bulan_ini' => Laporan::where('bulan', now()->translatedFormat('F'))
                ->where('tahun', now()->year)
                ->count(),
        ];

        $laporanTerbaru = Laporan::with(['user', 'jenisRhk'])
            ->orderByDesc('created_at')
            ->limit(5)
            ->get();

        return view('admin.dashboard', compact('stats', 'laporanTerbaru'));
    }
}
