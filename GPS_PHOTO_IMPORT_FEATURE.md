# Fitur Import GPS Photo ke Laporan

## 📋 Ringkasan

Fitur ini memungkinkan user untuk memilih foto GPS yang sudah di-watermark saat membuat laporan baru. Foto GPS akan ditampilkan di laporan yang dibuat.

## 🗄️ Database Changes

### Migration: `add_gps_photo_id_to_laporans_table`

Menambahkan kolom baru ke tabel `laporans`:

```sql
ALTER TABLE laporans ADD COLUMN gps_photo_id BIGINT UNSIGNED NULLABLE;
ALTER TABLE laporans ADD FOREIGN KEY (gps_photo_id) REFERENCES gps_photos(id) ON DELETE SET NULL;
```

**Fitur:**
- Foreign key ke `gps_photos` table
- Nullable (opsional)
- Cascade delete: jika foto GPS dihapus, laporan tetap ada tapi `gps_photo_id` menjadi NULL

## 📊 Model Updates

### Laporan Model

```php
// Relationship
public function gpsPhoto(): BelongsTo
{
    return $this->belongsTo(GpsPhoto::class);
}

// Fillable
protected $fillable = [
    'user_id',
    'gps_photo_id',  // ← NEW
    // ... fields lainnya
];
```

## 🎮 Controller Methods

### LaporanController

#### `create()` - Updated
```php
public function create(): View
{
    // ... existing code ...
    
    // Get GPS photos for this user
    $gpsPhotos = $user->gpsPhotos()
        ->orderByDesc('created_at')
        ->get();

    return view('laporan.create', compact('rhks', 'user', 'templates', 'gpsPhotos'));
}
```

#### `getGpsPhotos()` - NEW
```php
public function getGpsPhotos(): JsonResponse
{
    $user = auth()->user();
    $photos = $user->gpsPhotos()
        ->orderByDesc('created_at')
        ->get(['id', 'filename', 'original_filename', 'latitude', 'longitude', 'address', 'created_at']);

    return response()->json($photos);
}
```

## 🛣️ Routes

```php
// Get GPS photos as JSON (untuk AJAX)
Route::get('/gps-photos', [LaporanController::class, 'getGpsPhotos'])->name('laporan.gps-photos');
```

## 🎨 Frontend - Create Laporan Form

### GPS Photo Picker Section

**Lokasi:** Setelah RHK & Periode, sebelum Header Instansi

**Fitur:**
- Tampil hanya jika user memiliki GPS photos
- Grid layout 2-3 kolom
- Radio button untuk memilih foto
- Preview thumbnail dengan hover effect
- Menampilkan nama file dan tanggal
- Link "Buat foto baru" ke `/gps-photo`

**HTML:**
```html
<div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
    <div class="flex items-center justify-between mb-4">
        <h3 class="text-sm font-semibold text-gray-900 dark:text-white">2. Foto GPS (Opsional)</h3>
        <a href="{{ route('gps-photo.index') }}" class="text-xs text-blue-600 dark:text-blue-400 hover:underline">
            Buat foto baru
        </a>
    </div>
    
    <div class="grid grid-cols-2 sm:grid-cols-3 gap-3">
        @foreach ($gpsPhotos as $photo)
            <label class="relative cursor-pointer group">
                <input type="radio" name="gps_photo_id" value="{{ $photo->id }}" class="hidden peer">
                <div class="relative h-24 rounded-xl border-2 border-gray-300 dark:border-gray-700 overflow-hidden peer-checked:border-blue-500 peer-checked:ring-2 peer-checked:ring-blue-500 transition">
                    <img src="{{ Storage::url('gps-photos/' . $photo->filename) }}" 
                         alt="{{ $photo->original_filename }}"
                         class="w-full h-full object-cover">
                    <div class="absolute inset-0 bg-black/0 group-hover:bg-black/30 peer-checked:bg-blue-500/20 transition flex items-center justify-center">
                        <svg class="w-6 h-6 text-white opacity-0 group-hover:opacity-100 peer-checked:opacity-100 transition" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd"/>
                        </svg>
                    </div>
                </div>
                <p class="text-xs text-gray-600 dark:text-gray-400 mt-1 truncate">{{ $photo->original_filename }}</p>
                <p class="text-xs text-gray-500 dark:text-gray-500">{{ $photo->created_at->format('d M Y') }}</p>
            </label>
        @endforeach
    </div>
</div>
```

### Section Numbering

Nomor section di form akan berubah dinamis:

| Kondisi | Nomor Section |
|---------|---------------|
| Tanpa GPS Photos | 1. RHK, 2. Header, 3. Isi, 4. TTD, 5. Dokumentasi |
| Dengan GPS Photos | 1. RHK, 2. GPS Photo, 3. Header, 4. Isi, 5. TTD, 6. Dokumentasi |

## 💾 Data Flow

### Saat Create Laporan

1. User memilih GPS photo (opsional)
2. Form di-submit dengan `gps_photo_id`
3. Controller menyimpan `gps_photo_id` ke database
4. Laporan berhasil dibuat dengan referensi ke GPS photo

### Saat View/Edit Laporan

1. Laporan ditampilkan dengan foto GPS (jika ada)
2. User bisa melihat foto GPS yang digunakan
3. Saat edit, bisa mengganti atau menghapus GPS photo

## 🔒 Authorization

- User hanya bisa melihat GPS photos milik mereka sendiri
- GPS photo yang dihapus tidak akan menghapus laporan (cascade delete set NULL)

## 📱 Responsive Design

- **Desktop:** Grid 3 kolom
- **Tablet:** Grid 2 kolom
- **Mobile:** Grid 2 kolom

## 🎯 User Experience

1. **Membuat Laporan Baru:**
   - Buka `/laporan/create`
   - Pilih RHK & Periode
   - (Opsional) Pilih GPS Photo dari galeri
   - Isi form laporan
   - Submit

2. **Jika Belum Ada GPS Photo:**
   - Klik "Buat foto baru" di form
   - Akan redirect ke `/gps-photo`
   - Buat foto GPS baru
   - Kembali ke form create laporan
   - Foto baru akan muncul di picker

3. **Mengelola GPS Photos:**
   - Buka `/gps-photo/gallery`
   - Lihat semua foto dengan grouping per tanggal
   - Download atau delete foto
   - Foto yang digunakan di laporan tetap aman (tidak bisa dihapus dari laporan)

## 🔄 Integration Points

### Existing Features
- ✅ Subscription check (GPS photo hanya untuk user dengan langganan aktif)
- ✅ User authorization (hanya user pemilik bisa akses)
- ✅ Template system (GPS photo tidak termasuk di template)
- ✅ Export PDF/DOCX (GPS photo bisa ditampilkan di laporan)

### Future Enhancements
- [ ] Tampilkan GPS photo di PDF/DOCX export
- [ ] Crop/edit GPS photo sebelum import
- [ ] Batch import multiple GPS photos
- [ ] GPS photo metadata di laporan (koordinat, alamat, dll)

## 📝 Testing Checklist

- [ ] Create laporan tanpa GPS photo
- [ ] Create laporan dengan GPS photo
- [ ] Edit laporan untuk mengganti GPS photo
- [ ] Delete GPS photo (laporan tetap ada)
- [ ] View laporan dengan GPS photo
- [ ] Export PDF/DOCX dengan GPS photo
- [ ] Mobile responsiveness
- [ ] Authorization (user A tidak bisa lihat GPS photo user B)

## 🚀 Deployment Notes

1. Run migration: `php artisan migrate`
2. Build assets: `npm run build`
3. Clear cache: `php artisan cache:clear`
4. Test di staging sebelum production

## 📚 Related Documentation

- [GPS Photo Feature](GPS_PHOTO_FEATURE.md)
- [Subscription Model](IMPLEMENTATION_SUMMARY.md)
- [Laporan Feature](FITUR_BARU.md)
