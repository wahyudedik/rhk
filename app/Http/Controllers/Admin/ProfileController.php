<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rules\Password;
use Illuminate\View\View;

class ProfileController extends Controller
{
    public function edit(Request $request): View
    {
        return view('admin.profile.edit', ['user' => $request->user()]);
    }

    public function update(ProfileUpdateRequest $request): RedirectResponse
    {
        $validated = $request->validated();

        // TTD canvas prioritas
        if (! empty($validated['tanda_tangan_canvas'])) {
            if ($request->user()->tanda_tangan) {
                Storage::disk('public')->delete($request->user()->tanda_tangan);
            }
            $validated['tanda_tangan'] = $this->saveBase64Image($validated['tanda_tangan_canvas'], 'ttd-user');
        } elseif ($request->hasFile('tanda_tangan')) {
            if ($request->user()->tanda_tangan) {
                Storage::disk('public')->delete($request->user()->tanda_tangan);
            }
            $validated['tanda_tangan'] = $request->file('tanda_tangan')->store('ttd-user', 'public');
        }
        unset($validated['tanda_tangan_canvas']);

        $request->user()->fill($validated);

        if ($request->user()->isDirty('email')) {
            $request->user()->email_verified_at = null;
        }

        $request->user()->save();

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Profil berhasil diperbarui.');
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded = base64_decode($data);
        $filename = $folder.'/'.uniqid('ttd_', true).'.png';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    public function updatePassword(Request $request): RedirectResponse
    {
        $validated = $request->validateWithBag('updatePassword', [
            'current_password' => ['required', 'current_password'],
            'password' => ['required', Password::defaults(), 'confirmed'],
        ]);

        $request->user()->update([
            'password' => Hash::make($validated['password']),
        ]);

        return redirect()->route('admin.profile.edit')
            ->with('success', 'Password berhasil diperbarui.')
            ->with('password_updated', true);
    }
}
