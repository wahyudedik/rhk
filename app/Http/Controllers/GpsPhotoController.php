<?php

namespace App\Http\Controllers;

use App\Models\GpsPhoto;
use Illuminate\Http\Request;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Support\Facades\Storage;
use Carbon\Carbon;

class GpsPhotoController extends Controller
{
    public function index(): View
    {
        return view('gps-photo.index');
    }

    public function gallery(): View
    {
        $user = auth()->user();
        $photos = $user->gpsPhotos()
            ->orderBy('created_at', 'desc')
            ->get()
            ->groupBy(function ($photo) {
                $date = $photo->created_at->toDateString();
                $today = now()->toDateString();
                $yesterday = now()->subDay()->toDateString();

                if ($date === $today) {
                    return 'Hari Ini';
                } elseif ($date === $yesterday) {
                    return 'Kemarin';
                } else {
                    $createdDate = $photo->created_at;
                    $now = now();

                    // Same month and year
                    if ($createdDate->month === $now->month && $createdDate->year === $now->year) {
                        return $createdDate->format('d F');
                    }
                    // Same year
                    elseif ($createdDate->year === $now->year) {
                        return $createdDate->format('F');
                    }
                    // Different year
                    else {
                        return $createdDate->format('Y');
                    }
                }
            });

        return view('gps-photo.gallery', compact('photos'));
    }

    public function store(Request $request): JsonResponse
    {
        $user = auth()->user();
        $subscription = $user->activeSubscription();

        if (!$subscription) {
            return response()->json([
                'success' => false,
                'message' => 'Anda harus berlangganan untuk menggunakan fitur ini',
            ], 403);
        }

        // Check GPS photo limit
        $limit = $subscription->billingPlan->batas_gps_photo_per_bulan;
        if ($limit !== null) {
            $currentMonth = now()->format('Y-m');
            $photosThisMonth = $user->gpsPhotos()
                ->whereRaw("DATE_FORMAT(created_at, '%Y-%m') = ?", [$currentMonth])
                ->count();

            if ($photosThisMonth >= $limit) {
                return response()->json([
                    'success' => false,
                    'message' => "Anda telah mencapai batas {$limit} foto GPS untuk bulan ini",
                ], 403);
            }
        }

        $request->validate([
            'image' => 'required|string',
            'filename' => 'required|string',
            'latitude' => 'required|numeric',
            'longitude' => 'required|numeric',
            'address' => 'nullable|string',
            'altitude' => 'nullable|numeric',
            'speed' => 'nullable|numeric',
            'photo_datetime' => 'required|date_format:Y-m-d\TH:i',
        ]);

        try {
            // Decode base64 image
            $imageData = $request->input('image');
            if (str_starts_with($imageData, 'data:image')) {
                $imageData = substr($imageData, strpos($imageData, ',') + 1);
            }
            $imageData = base64_decode($imageData);

            // Generate filename
            $filename = 'gps-photo-' . $user->id . '-' . time() . '.png';
            $path = 'gps-photos/' . $filename;

            // Store image
            Storage::disk('public')->put($path, $imageData);

            // Save to database
            $gpsPhoto = GpsPhoto::create([
                'user_id' => $user->id,
                'filename' => $filename,
                'original_filename' => $request->input('filename'),
                'latitude' => $request->input('latitude'),
                'longitude' => $request->input('longitude'),
                'address' => $request->input('address'),
                'altitude' => $request->input('altitude'),
                'speed' => $request->input('speed'),
                'photo_datetime' => $request->input('photo_datetime'),
            ]);

            return response()->json([
                'success' => true,
                'message' => 'Foto GPS berhasil disimpan',
                'photo' => $gpsPhoto,
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menyimpan foto: ' . $e->getMessage(),
            ], 500);
        }
    }

    public function destroy(GpsPhoto $gpsPhoto): JsonResponse
    {
        $this->authorize('delete', $gpsPhoto);

        try {
            Storage::disk('public')->delete('gps-photos/' . $gpsPhoto->filename);
            $gpsPhoto->delete();

            return response()->json([
                'success' => true,
                'message' => 'Foto GPS berhasil dihapus',
            ]);
        } catch (\Exception $e) {
            return response()->json([
                'success' => false,
                'message' => 'Gagal menghapus foto: ' . $e->getMessage(),
            ], 500);
        }
    }
}
