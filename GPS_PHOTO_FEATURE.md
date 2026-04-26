# Fitur GPS Photo - Dokumentasi

## Deskripsi
Fitur GPS Photo memungkinkan user untuk menambahkan watermark GPS ke foto dokumentasi. Watermark mencakup:
- **Tanggal & Waktu** (format: DD MMM YYYY HH:MM:SS)
- **Koordinat GPS** (Latitude & Longitude)
- **Alamat Lengkap** (dengan word wrap otomatis)
- **Altitude** (ketinggian dalam meter)
- **Speed** (kecepatan dalam km/h)
- **Mini Map** (peta lokasi di pojok kiri bawah)

Hasil akhir dapat diunduh dalam format **PNG**.

---

## Cara Menggunakan

### 1. Akses Fitur
- Buka aplikasi dan login sebagai pelanggan
- Klik menu **GPS Foto** di sidebar
- URL: `http://laporan-asn.test/gps-photo`

### 2. Upload Foto
- Klik area upload atau drag-drop foto
- Format yang didukung: JPG, PNG
- Ukuran maksimal: 10MB
- Preview foto akan muncul di panel kanan

### 3. Atur Koordinat GPS
Ada 3 cara untuk mengatur koordinat:

#### Cara A: Input Manual
- Masukkan **Latitude** dan **Longitude** secara manual
- Contoh: `-7.4060877` dan `112.4733533`
- Klik tombol **🗺️ Update Peta** untuk update peta

#### Cara B: Gunakan Lokasi Saya
- Klik tombol **📍 Lokasi Saya**
- Browser akan meminta izin akses lokasi
- Koordinat, altitude, dan speed akan terisi otomatis
- Alamat akan di-reverse geocode dari OpenStreetMap

#### Cara C: Drag Marker di Peta
- Klik dan drag marker merah di peta
- Koordinat akan update otomatis

### 4. Isi Data Tambahan
- **Alamat Lengkap**: Alamat lokasi (opsional, bisa auto-fill dari reverse geocode)
- **Altitude**: Ketinggian lokasi dalam meter (default: 49.8)
- **Speed**: Kecepatan dalam km/h (default: 0.0)
- **Tanggal & Waktu**: Otomatis terisi dengan waktu saat ini, bisa diubah

### 5. Generate GPS Photo
- Klik tombol **🎯 Generate GPS Foto**
- Sistem akan:
  1. Membuat mini map dari Leaflet
  2. Menggabungkan foto dengan watermark GPS
  3. Menampilkan preview di panel kanan

### 6. Download Foto
- Klik tombol **💾 Download Foto GPS**
- Foto akan diunduh dengan nama: `gps-photo-{timestamp}.png`

---

## Teknologi yang Digunakan

### Frontend Libraries
- **Leaflet.js** (v1.9.4) - Peta interaktif
  - URL: https://unpkg.com/leaflet@1.9.4/
  - Tile provider: OpenStreetMap
  
- **html2canvas** - Capture mini map ke canvas
  - URL: https://html2canvas.hertzen.com/
  
- **Geolocation API** - Browser native API untuk mendapatkan lokasi real-time

### Backend
- Laravel 13 (PHP 8.4)
- Controller: `App\Http\Controllers\GpsPhotoController`
- Route: `/gps-photo` (GET)

---

## Fitur Detail

### Mini Map
- Ukuran: 120x120 pixel
- Zoom level: 16
- Posisi: Pojok kiri bawah foto
- Background: Putih semi-transparan (opacity 0.9)
- Tile provider: OpenStreetMap

### Watermark Overlay
- Posisi: Bagian bawah foto
- Background: Gradient hitam (opacity 0.7 - 0.9)
- Tinggi: ~120 pixel
- Teks alignment: Right-aligned
- Font: Arial

### Teks Watermark
```
23 Feb 2026 11:38:14
7.4060877S 112.4733533E
No.27 Jalan Raya Jetis, Kecamatan Jetis, 
Kabupaten Mojokerto, Jawa Timur
Altitude:49.8msnm Speed:0.0km/h
```

---

## Contoh Koordinat Default
- **Lokasi**: Kantor Dinas Sosial Mojokerto
- **Latitude**: -7.4060877
- **Longitude**: 112.4733533
- **Alamat**: No.27 Jalan Raya Jetis, Kecamatan Jetis, Kabupaten Mojokerto, Jawa Timur
- **Altitude**: 49.8 msnm
- **Speed**: 0.0 km/h

---

## Batasan & Catatan

### Batasan
1. **Ukuran Foto**: Maksimal 10MB
2. **Format Output**: PNG (lossless)
3. **Resolusi**: Tergantung foto input (max width 800px untuk preview)
4. **Mini Map**: Membutuhkan koneksi internet untuk load tile OpenStreetMap

### Catatan Penting
- **Geolocation**: Hanya bekerja di HTTPS atau localhost
- **Reverse Geocoding**: Menggunakan Nominatim OpenStreetMap (rate limit: 1 request/detik)
- **Canvas Size**: Otomatis scale sesuai ukuran foto original
- **Browser Support**: Chrome, Firefox, Safari, Edge (modern browsers)

---

## Troubleshooting

### "Geolocation tidak didukung browser Anda"
- Gunakan browser modern (Chrome, Firefox, Safari, Edge)
- Pastikan HTTPS atau localhost

### "Lokasi tidak bisa diakses"
- Izinkan akses lokasi di browser settings
- Pastikan GPS/Location Services aktif di device

### Mini map tidak muncul
- Tunggu 2 detik setelah klik "Generate GPS Foto"
- Pastikan koneksi internet stabil
- Cek browser console untuk error

### Foto tidak bisa diunduh
- Pastikan browser tidak memblokir download
- Cek folder Downloads
- Coba browser lain

---

## File Struktur

```
resources/
├── views/
│   └── gps-photo/
│       └── index.blade.php          # View utama GPS Photo
│
app/
└── Http/
    └── Controllers/
        └── GpsPhotoController.php   # Controller GPS Photo

routes/
└── web.php                          # Route definition
```

---

## Integrasi dengan Laporan

Foto yang sudah di-generate dengan GPS watermark bisa:
1. Diupload ke halaman **Buat Laporan** sebagai foto dokumentasi
2. Disimpan ke device untuk dokumentasi offline
3. Dibagikan langsung ke stakeholder

---

## Rencana Pengembangan (Future)

- [ ] Batch processing multiple photos
- [ ] Custom watermark template
- [ ] Export ke format JPEG/WebP
- [ ] Simpan GPS metadata ke EXIF
- [ ] Integration dengan Google Maps
- [ ] Offline map support
- [ ] QR code generation
- [ ] Watermark dengan logo custom

---

## Support & Feedback

Untuk pertanyaan atau feedback, hubungi tim development.
