<?php

namespace App\Http\Controllers\Admin;

use App\Enums\Role;
use App\Http\Controllers\Controller;
use App\Http\Requests\Admin\StoreUserRequest;
use App\Http\Requests\Admin\UpdateUserRequest;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\View\View;

class UserController extends Controller
{
    public function index(): View
    {
        $users = User::orderByDesc('created_at')->paginate(10);

        return view('admin.users.index', compact('users'));
    }

    public function show(User $user): View
    {
        // Hitung stats via query langsung, tidak load semua ke memori
        $totalLaporan = $user->laporans()->count();
        $laporanBulanIni = $user->laporans()
            ->where('bulan', now()->translatedFormat('F'))
            ->where('tahun', now()->year)
            ->count();
        $tahunAktif = $user->laporans()
            ->distinct()
            ->orderByDesc('tahun')
            ->pluck('tahun');

        $stats = [
            'total_laporan' => $totalLaporan,
            'laporan_bulan_ini' => $laporanBulanIni,
            'tahun_aktif' => $tahunAktif,
        ];

        $laporanTerbaru = $user->laporans()
            ->with(['rhk', 'jenisRhk'])
            ->orderByDesc('created_at')
            ->paginate(10);

        return view('admin.users.show', compact('user', 'stats', 'laporanTerbaru'));
    }

    public function create(): View
    {
        $roles = Role::cases();

        return view('admin.users.create', compact('roles'));
    }

    public function store(StoreUserRequest $request): RedirectResponse
    {
        User::create($request->validated());

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil ditambahkan.');
    }

    public function edit(User $user): View
    {
        $roles = Role::cases();

        return view('admin.users.edit', compact('user', 'roles'));
    }

    public function update(UpdateUserRequest $request, User $user): RedirectResponse
    {
        $validated = $request->validated();

        // Jangan update password jika tidak diisi
        if (empty($validated['password'])) {
            unset($validated['password']);
        }

        $user->update($validated);

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil diperbarui.');
    }

    public function destroy(User $user): RedirectResponse
    {
        abort_if($user->isSuperadmin(), 403, 'Akun superadmin tidak dapat dihapus.');

        $user->delete();

        return redirect()->route('admin.users.index')
            ->with('success', 'Pengguna berhasil dihapus.');
    }
}
