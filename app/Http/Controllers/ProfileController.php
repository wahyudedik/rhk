<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProfileUpdateRequest;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;

class ProfileController extends Controller
{
    /**
     * Display the user's profile form.
     */
    public function edit(Request $request): View
    {
        return view('profile.edit', [
            'user' => $request->user(),
        ]);
    }

    /**
     * Update the user's profile information.
     */
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

        return Redirect::route('profile.edit')->with('status', 'profile-updated');
    }

    private function saveBase64Image(string $base64, string $folder): string
    {
        $data = preg_replace('/^data:image\/\w+;base64,/', '', $base64);
        $decoded = base64_decode($data);
        $filename = $folder.'/'.uniqid('ttd_', true).'.png';
        Storage::disk('public')->put($filename, $decoded);

        return $filename;
    }

    /**
     * Delete the user's account.
     */
    public function destroy(Request $request): RedirectResponse
    {
        $request->validateWithBag('userDeletion', [
            'password' => ['required', 'current_password'],
        ]);

        $user = $request->user();

        Auth::logout();

        $user->delete();

        $request->session()->invalidate();
        $request->session()->regenerateToken();

        return Redirect::to('/');
    }
}
