<x-app-layout>
    <x-slot name="title">GPS Foto</x-slot>
    <x-slot name="header">
        <h2 class="text-base font-semibold text-gray-900 dark:text-white">GPS Foto</h2>
    </x-slot>

    <div class="max-w-6xl mx-auto">
        <div class="mb-5">
            <h1 class="text-xl font-bold text-gray-900 dark:text-white">GPS Foto</h1>
            <p class="text-sm text-gray-500 dark:text-gray-400 mt-0.5">
                Upload foto dan tambahkan watermark GPS dengan lokasi, tanggal, dan peta mini.
            </p>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            {{-- Panel Kiri: Upload & Settings --}}
            <div class="space-y-4">
                {{-- Upload Foto --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">1. Upload Foto</h3>
                    <div class="space-y-3">
                        <label class="flex flex-col items-center justify-center w-full h-32 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl cursor-pointer hover:border-blue-400 transition bg-gray-50 dark:bg-gray-800/50">
                            <svg class="w-8 h-8 text-gray-400 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <span class="text-sm text-gray-500">Pilih foto (JPG, PNG, max 10MB)</span>
                            <input type="file" id="photo-input" accept="image/*" class="hidden">
                        </label>
                        <div id="photo-preview" class="hidden">
                            <img id="photo-img" class="w-full h-48 object-cover rounded-xl border border-gray-200 dark:border-gray-700">
                        </div>
                    </div>
                </div>

                {{-- GPS Settings --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">2. Pengaturan GPS</h3>
                    <div class="space-y-4">
                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Latitude</label>
                                <input type="text" id="latitude" value="-7.4060877" 
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Longitude</label>
                                <input type="text" id="longitude" value="112.4733533" 
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>
                        
                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Alamat Lengkap</label>
                            <textarea id="address" rows="3" placeholder="No.27 Jalan Raya Jetis, Kecamatan Jetis, Kabupaten Mojokerto, Jawa Timur"
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition resize-none">No.27 Jalan Raya Jetis, Kecamatan Jetis, Kabupaten Mojokerto, Jawa Timur</textarea>
                        </div>

                        <div class="grid grid-cols-2 gap-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Altitude (m)</label>
                                <input type="text" id="altitude" value="49.8" 
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                            <div>
                                <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Speed (km/h)</label>
                                <input type="text" id="speed" value="0.0" 
                                    class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                            </div>
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-600 dark:text-gray-400 mb-1">Tanggal & Waktu</label>
                            <input type="datetime-local" id="datetime" 
                                class="w-full px-3 py-2 text-sm rounded-xl border border-gray-300 dark:border-gray-700 bg-white dark:bg-gray-800 text-gray-900 dark:text-gray-100 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent transition">
                        </div>

                        <div class="flex gap-2">
                            <button type="button" id="get-location" 
                                class="flex-1 px-4 py-2 text-sm font-medium text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800">
                                📍 Lokasi Saya
                            </button>
                            <button type="button" id="update-map" 
                                class="flex-1 px-4 py-2 text-sm font-medium text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950 rounded-xl hover:bg-green-100 dark:hover:bg-green-900 transition border border-green-200 dark:border-green-800">
                                🗺️ Update Peta
                            </button>
                        </div>
                    </div>
                </div>

                {{-- Actions --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">3. Generate & Download</h3>
                    <div class="space-y-3">
                        <button type="button" id="generate-gps-photo" disabled
                            class="w-full px-4 py-3 text-sm font-semibold text-white bg-blue-600 rounded-xl hover:bg-blue-700 transition shadow-sm shadow-blue-500/20 disabled:bg-gray-400 disabled:cursor-not-allowed">
                            🎯 Generate GPS Foto
                        </button>
                        <button type="button" id="save-photo" disabled
                            class="w-full px-4 py-3 text-sm font-semibold text-blue-700 dark:text-blue-400 bg-blue-50 dark:bg-blue-950 rounded-xl hover:bg-blue-100 dark:hover:bg-blue-900 transition border border-blue-200 dark:border-blue-800 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            💾 Simpan Foto GPS
                        </button>
                        <button type="button" id="download-photo" disabled
                            class="w-full px-4 py-3 text-sm font-semibold text-green-700 dark:text-green-400 bg-green-50 dark:bg-green-950 rounded-xl hover:bg-green-100 dark:hover:bg-green-900 transition border border-green-200 dark:border-green-800 disabled:bg-gray-100 disabled:text-gray-400 disabled:cursor-not-allowed">
                            ⬇️ Download Foto GPS
                        </button>
                        <a href="{{ route('gps-photo.gallery') }}" class="block w-full px-4 py-3 text-sm font-semibold text-center text-purple-700 dark:text-purple-400 bg-purple-50 dark:bg-purple-950 rounded-xl hover:bg-purple-100 dark:hover:bg-purple-900 transition border border-purple-200 dark:border-purple-800">
                            📷 Lihat Galeri Foto
                        </a>
                    </div>
                </div>
            </div>

            {{-- Panel Kanan: Preview & Map --}}
            <div class="space-y-4">
                {{-- Map --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Peta Lokasi</h3>
                    <div id="map" class="w-full h-64 rounded-xl border border-gray-200 dark:border-gray-700"></div>
                </div>

                {{-- Preview --}}
                <div class="bg-white dark:bg-gray-900 rounded-2xl border border-gray-200 dark:border-gray-800 p-5">
                    <h3 class="text-sm font-semibold text-gray-900 dark:text-white mb-4">Preview GPS Foto</h3>
                    <div id="preview-container" class="relative">
                        <div id="no-preview" class="flex flex-col items-center justify-center h-64 border-2 border-dashed border-gray-300 dark:border-gray-700 rounded-xl text-gray-400">
                            <svg class="w-12 h-12 mb-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"/>
                            </svg>
                            <p class="text-sm">Upload foto untuk melihat preview</p>
                        </div>
                        <canvas id="gps-canvas" class="hidden w-full rounded-xl border border-gray-200 dark:border-gray-700"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>

    @push('styles')
    <link rel="stylesheet" href="https://unpkg.com/leaflet@1.9.4/dist/leaflet.css" />
    <style>
        .leaflet-container { background: #f8fafc; }
        .dark .leaflet-container { background: #1f2937; }
    </style>
    @endpush

    @push('scripts')
    <script src="https://unpkg.com/leaflet@1.9.4/dist/leaflet.js"></script>
    <script src="https://html2canvas.hertzen.com/dist/html2canvas.min.js"></script>
    <script>
    // GPS Photo App
    class GpsPhotoApp {
        constructor() {
            this.map = null;
            this.marker = null;
            this.uploadedImage = null;
            this.canvas = document.getElementById('gps-canvas');
            this.ctx = this.canvas.getContext('2d');
            
            this.init();
        }

        init() {
            this.initMap();
            this.bindEvents();
            this.setCurrentDateTime();
        }

        initMap() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            this.map = L.map('map').setView([lat, lng], 15);
            
            L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                attribution: '© OpenStreetMap contributors'
            }).addTo(this.map);
            
            this.marker = L.marker([lat, lng], { draggable: true }).addTo(this.map);
            
            this.marker.on('dragend', (e) => {
                const pos = e.target.getLatLng();
                document.getElementById('latitude').value = pos.lat.toFixed(7);
                document.getElementById('longitude').value = pos.lng.toFixed(7);
            });
        }

        bindEvents() {
            // Photo upload
            document.getElementById('photo-input').addEventListener('change', (e) => {
                this.handlePhotoUpload(e);
            });

            // Get current location
            document.getElementById('get-location').addEventListener('click', () => {
                this.getCurrentLocation();
            });

            // Update map
            document.getElementById('update-map').addEventListener('click', () => {
                this.updateMap();
            });

            // Generate GPS photo
            document.getElementById('generate-gps-photo').addEventListener('click', () => {
                this.generateGpsPhoto();
            });

            // Save photo
            document.getElementById('save-photo').addEventListener('click', () => {
                this.savePhoto();
            });

            // Download photo
            document.getElementById('download-photo').addEventListener('click', () => {
                this.downloadPhoto();
            });
        }

        setCurrentDateTime() {
            const now = new Date();
            const offset = now.getTimezoneOffset() * 60000;
            const localTime = new Date(now.getTime() - offset);
            document.getElementById('datetime').value = localTime.toISOString().slice(0, 16);
        }

        handlePhotoUpload(e) {
            const file = e.target.files[0];
            if (!file) return;

            const reader = new FileReader();
            reader.onload = (event) => {
                const img = new Image();
                img.onload = () => {
                    this.uploadedImage = img;
                    document.getElementById('photo-img').src = event.target.result;
                    document.getElementById('photo-preview').classList.remove('hidden');
                    document.getElementById('generate-gps-photo').disabled = false;
                };
                img.src = event.target.result;
            };
            reader.readAsDataURL(file);
        }

        getCurrentLocation() {
            if (!navigator.geolocation) {
                alert('Geolocation tidak didukung browser Anda');
                return;
            }

            const btn = document.getElementById('get-location');
            btn.textContent = '📍 Mencari lokasi...';
            btn.disabled = true;

            navigator.geolocation.getCurrentPosition(
                (position) => {
                    try {
                        const lat = position.coords.latitude;
                        const lng = position.coords.longitude;
                        
                        document.getElementById('latitude').value = lat.toFixed(7);
                        document.getElementById('longitude').value = lng.toFixed(7);
                        
                        if (position.coords.altitude) {
                            document.getElementById('altitude').value = position.coords.altitude.toFixed(1);
                        }
                        
                        if (position.coords.speed) {
                            document.getElementById('speed').value = (position.coords.speed * 3.6).toFixed(1);
                        }

                        this.updateMap();
                        this.reverseGeocode(lat, lng);
                        
                        btn.textContent = '📍 Lokasi Saya';
                        btn.disabled = false;
                    } catch (error) {
                        console.error('Error processing location:', error);
                        alert('Error memproses lokasi: ' + error.message);
                        btn.textContent = '📍 Lokasi Saya';
                        btn.disabled = false;
                    }
                },
                (error) => {
                    let errorMsg = 'Gagal mendapatkan lokasi';
                    
                    switch(error.code) {
                        case error.PERMISSION_DENIED:
                            errorMsg = 'Izin akses lokasi ditolak. Silakan aktifkan di browser settings.';
                            break;
                        case error.POSITION_UNAVAILABLE:
                            errorMsg = 'Informasi lokasi tidak tersedia.';
                            break;
                        case error.TIMEOUT:
                            errorMsg = 'Timeout mencari lokasi. Coba lagi.';
                            break;
                    }
                    
                    alert(errorMsg);
                    btn.textContent = '📍 Lokasi Saya';
                    btn.disabled = false;
                },
                {
                    enableHighAccuracy: false,
                    timeout: 10000,
                    maximumAge: 0
                }
            );
        }

        async reverseGeocode(lat, lng) {
            try {
                const response = await fetch(`https://nominatim.openstreetmap.org/reverse?format=json&lat=${lat}&lon=${lng}&addressdetails=1`);
                const data = await response.json();
                
                if (data.display_name) {
                    document.getElementById('address').value = data.display_name;
                }
            } catch (error) {
                console.log('Reverse geocoding failed:', error);
            }
        }

        updateMap() {
            const lat = parseFloat(document.getElementById('latitude').value);
            const lng = parseFloat(document.getElementById('longitude').value);
            
            if (isNaN(lat) || isNaN(lng)) {
                alert('Koordinat tidak valid');
                return;
            }
            
            this.map.setView([lat, lng], 15);
            this.marker.setLatLng([lat, lng]);
        }

        generateGpsPhoto() {
            if (!this.uploadedImage) {
                alert('Silakan upload foto terlebih dahulu');
                return;
            }

            const btn = document.getElementById('generate-gps-photo');
            btn.textContent = '🎯 Generating...';
            btn.disabled = true;

            // Set canvas size to match image
            const maxWidth = 800;
            const ratio = Math.min(maxWidth / this.uploadedImage.width, maxWidth / this.uploadedImage.height);
            
            this.canvas.width = this.uploadedImage.width * ratio;
            this.canvas.height = this.uploadedImage.height * ratio;

            // Clear canvas
            this.ctx.clearRect(0, 0, this.canvas.width, this.canvas.height);

            // Draw main image
            this.ctx.drawImage(this.uploadedImage, 0, 0, this.canvas.width, this.canvas.height);

            // Generate mini map and overlay
            this.generateMiniMap().then(() => {
                this.drawGpsOverlay();
                
                // Show canvas
                document.getElementById('no-preview').classList.add('hidden');
                this.canvas.classList.remove('hidden');
                document.getElementById('download-photo').disabled = false;
                document.getElementById('save-photo').disabled = false;
                
                btn.textContent = '🎯 Generate GPS Foto';
                btn.disabled = false;
            }).catch(err => {
                console.error('Error generating GPS photo:', err);
                btn.textContent = '🎯 Generate GPS Foto';
                btn.disabled = false;
            });
        }

        async generateMiniMap() {
            return new Promise((resolve) => {
                try {
                    // Create mini map container - LEBIH BESAR
                    const miniMapDiv = document.createElement('div');
                    miniMapDiv.style.width = '200px';
                    miniMapDiv.style.height = '200px';
                    miniMapDiv.style.position = 'absolute';
                    miniMapDiv.style.left = '-9999px';
                    document.body.appendChild(miniMapDiv);

                    const lat = parseFloat(document.getElementById('latitude').value);
                    const lng = parseFloat(document.getElementById('longitude').value);

                    // Create mini map WITHOUT zoom controls and attribution
                    const miniMap = L.map(miniMapDiv, {
                        zoomControl: false,
                        attributionControl: false
                    }).setView([lat, lng], 16);
                    
                    L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
                        attribution: false
                    }).addTo(miniMap);
                    
                    L.marker([lat, lng]).addTo(miniMap);

                    // Wait for tiles to load then capture
                    setTimeout(() => {
                        try {
                            html2canvas(miniMapDiv, {
                                width: 200,
                                height: 200,
                                useCORS: true,
                                allowTaint: true,
                                backgroundColor: '#f0f0f0'
                            }).then((canvasResult) => {
                                try {
                                    // Draw mini map on main canvas - TOP RIGHT CORNER
                                    const miniMapSize = 200;
                                    const miniMapX = this.canvas.width - miniMapSize - 20;
                                    const miniMapY = 20;
                                    
                                    // White background for mini map
                                    this.ctx.fillStyle = 'rgba(255, 255, 255, 0.95)';
                                    this.ctx.fillRect(miniMapX - 5, miniMapY - 5, miniMapSize + 10, miniMapSize + 10);
                                    
                                    // Border
                                    this.ctx.strokeStyle = 'rgba(0, 0, 0, 0.3)';
                                    this.ctx.lineWidth = 2;
                                    this.ctx.strokeRect(miniMapX - 5, miniMapY - 5, miniMapSize + 10, miniMapSize + 10);
                                    
                                    // Draw mini map
                                    this.ctx.drawImage(canvasResult, miniMapX, miniMapY, miniMapSize, miniMapSize);
                                    
                                    // Clean up
                                    document.body.removeChild(miniMapDiv);
                                    resolve();
                                } catch (e) {
                                    console.warn('Error drawing mini map:', e);
                                    document.body.removeChild(miniMapDiv);
                                    resolve();
                                }
                            }).catch(err => {
                                console.warn('html2canvas failed:', err);
                                document.body.removeChild(miniMapDiv);
                                resolve();
                            });
                        } catch (e) {
                            console.warn('Error in html2canvas:', e);
                            document.body.removeChild(miniMapDiv);
                            resolve();
                        }
                    }, 2000);
                } catch (e) {
                    console.warn('Error generating mini map:', e);
                    resolve();
                }
            });
        }

        drawGpsOverlay() {
            const lat = document.getElementById('latitude').value;
            const lng = document.getElementById('longitude').value;
            const address = document.getElementById('address').value;
            const altitude = document.getElementById('altitude').value;
            const speed = document.getElementById('speed').value;
            const datetime = new Date(document.getElementById('datetime').value);

            // Format date
            const dateStr = datetime.toLocaleDateString('id-ID', {
                day: '2-digit',
                month: 'short',
                year: 'numeric'
            });
            const timeStr = datetime.toLocaleTimeString('id-ID', {
                hour: '2-digit',
                minute: '2-digit',
                second: '2-digit'
            });

            // Overlay background
            const overlayHeight = 120;
            const gradient = this.ctx.createLinearGradient(0, this.canvas.height - overlayHeight, 0, this.canvas.height);
            gradient.addColorStop(0, 'rgba(0, 0, 0, 0.7)');
            gradient.addColorStop(1, 'rgba(0, 0, 0, 0.9)');
            
            this.ctx.fillStyle = gradient;
            this.ctx.fillRect(0, this.canvas.height - overlayHeight, this.canvas.width, overlayHeight);

            // Text styling
            this.ctx.fillStyle = 'white';
            this.ctx.textAlign = 'right';
            
            const rightX = this.canvas.width - 20;
            let y = this.canvas.height - 95;

            // Date and time
            this.ctx.font = 'bold 16px Arial';
            this.ctx.fillText(`${dateStr} ${timeStr}`, rightX, y);
            y += 20;

            // Coordinates
            this.ctx.font = '14px Arial';
            this.ctx.fillText(`${lat}S ${lng}E`, rightX, y);
            y += 16;

            // Address (word wrap)
            this.ctx.font = '12px Arial';
            const maxWidth = this.canvas.width - 160;
            const words = address.split(' ');
            let line = '';
            
            for (let i = 0; i < words.length; i++) {
                const testLine = line + words[i] + ' ';
                const metrics = this.ctx.measureText(testLine);
                
                if (metrics.width > maxWidth && i > 0) {
                    this.ctx.fillText(line.trim(), rightX, y);
                    line = words[i] + ' ';
                    y += 14;
                } else {
                    line = testLine;
                }
            }
            this.ctx.fillText(line.trim(), rightX, y);
            y += 16;

            // Altitude and speed
            this.ctx.font = '11px Arial';
            this.ctx.fillText(`Altitude:${altitude}msnm Speed:${speed}km/h`, rightX, y);
        }

        downloadPhoto() {
            const link = document.createElement('a');
            link.download = `gps-photo-${Date.now()}.png`;
            link.href = this.canvas.toDataURL('image/png');
            link.click();
        }

        async savePhoto() {
            const btn = document.getElementById('save-photo');
            btn.textContent = '💾 Menyimpan...';
            btn.disabled = true;

            try {
                const imageData = this.canvas.toDataURL('image/png');
                const filename = document.getElementById('photo-input').files[0]?.name || 'gps-photo.png';

                const response = await fetch('{{ route("gps-photo.store") }}', {
                    method: 'POST',
                    headers: {
                        'Content-Type': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                    },
                    body: JSON.stringify({
                        image: imageData,
                        filename: filename,
                        latitude: document.getElementById('latitude').value,
                        longitude: document.getElementById('longitude').value,
                        address: document.getElementById('address').value,
                        altitude: document.getElementById('altitude').value,
                        speed: document.getElementById('speed').value,
                        photo_datetime: document.getElementById('datetime').value,
                    }),
                });

                const data = await response.json();

                if (data.success) {
                    alert('✅ Foto GPS berhasil disimpan!');
                    btn.textContent = '💾 Simpan Foto GPS';
                    btn.disabled = false;
                } else {
                    alert('❌ ' + data.message);
                    btn.textContent = '💾 Simpan Foto GPS';
                    btn.disabled = false;
                }
            } catch (error) {
                console.error('Error saving photo:', error);
                alert('❌ Gagal menyimpan foto: ' + error.message);
                btn.textContent = '💾 Simpan Foto GPS';
                btn.disabled = false;
            }
        }
    }

    // Initialize app when page loads
    document.addEventListener('DOMContentLoaded', () => {
        new GpsPhotoApp();
    });
    </script>
    @endpush
</x-app-layout>