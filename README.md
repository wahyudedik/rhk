# Sistem Laporan ASN

Aplikasi manajemen laporan untuk Aparatur Sipil Negara (ASN) berbasis Laravel 13 dengan sistem subscription dan multi-role. anajy

## 📋 Daftar Isi

- [Fitur Aplikasi](#-fitur-aplikasi)
- [Teknologi](#-teknologi)
- [Persyaratan Sistem](#-persyaratan-sistem)
- [Instalasi Lokal](#-instalasi-lokal)
- [Deployment ke VPS Ubuntu](#-deployment-ke-vps-ubuntu)
- [Konfigurasi Queue & Schedule](#-konfigurasi-queue--schedule)
- [Penggunaan](#-penggunaan)
- [Troubleshooting](#-troubleshooting)

## 🚀 Fitur Aplikasi

### 1. **Multi-Role System**
- **Superadmin**: Manajemen penuh sistem
- **Pelanggan**: User dengan subscription untuk membuat laporan

### 2. **Manajemen User (Superadmin)**
- CRUD user dengan role-based access
- Manajemen profil user (NIP, jabatan, desa, kecamatan, kabupaten, provinsi)
- Upload tanda tangan digital

### 3. **Sistem Subscription**
- Billing plan dengan fitur trial dan berbayar
- Batas laporan per bulan berdasarkan paket
- Auto-reset quota laporan setiap bulan
- Status subscription: active, expired, cancelled
- Middleware untuk cek subscription aktif

### 4. **Manajemen RHK (Rencana Hasil Kerja)**
- CRUD RHK dan Jenis RHK
- Hierarki: RHK → Jenis RHK
- Urutan custom untuk sorting

### 5. **Laporan ASN**
- Form laporan lengkap dengan:
  - Latar belakang, maksud & tujuan, ruang lingkup
  - Dasar, kegiatan, hasil, simpulan, saran, penutup
  - Header instansi (4 baris)
  - Tanda tangan (kota, tanggal, jabatan, nama, NIP, gambar)
  - Upload foto dokumentasi (multiple)
  - Keterangan dokumentasi
- Export laporan ke **PDF** dan **DOCX**
- Policy-based authorization (user hanya bisa edit/delete laporan sendiri)

### 6. **Dashboard**
- Dashboard Superadmin: Statistik user, subscription, laporan
- Dashboard Pelanggan: Statistik laporan pribadi, quota subscription

### 7. **Authentication**
- Laravel Breeze dengan Blade templates
- Email verification
- Password reset
- Profile management

## 🛠 Teknologi

### Backend
- **PHP**: 8.4
- **Laravel**: 13.6.0
- **Database**: MySQL
- **PDF Generator**: barryvdh/laravel-dompdf
- **DOCX Generator**: phpoffice/phpword

### Frontend
- **Alpine.js**: 3.15.11
- **Tailwind CSS**: 3.4.19
- **Vite**: 8.0.0

### Development Tools
- **Laravel Boost**: 2.4.5 (AI development tools)
- **Laravel Pint**: 1.29.1 (Code formatter)
- **PHPUnit**: 12.5.23 (Testing)

## 📦 Persyaratan Sistem

### Lokal Development
- PHP >= 8.3
- Composer
- Node.js >= 18
- MySQL >= 8.0
- Git

### VPS Ubuntu Production
- Ubuntu 22.04 LTS atau lebih baru
- PHP 8.4 dengan extensions: mbstring, xml, curl, zip, gd, mysql, bcmath
- MySQL 8.0
- Nginx
- Supervisor (untuk queue worker)
- Cron (untuk scheduler)

## 💻 Instalasi Lokal

### 1. Clone Repository
```bash
git clone <repository-url>
cd laporan-asn
```

### 2. Install Dependencies
```bash
composer install
npm install
```

### 3. Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

### 4. Konfigurasi Database
Edit file `.env`:
```env
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporan_asn
DB_USERNAME=root
DB_PASSWORD=
```

### 5. Migrasi & Seeding
```bash
php artisan migrate --seed
```

Seeder akan membuat:
- User superadmin (email: admin@example.com, password: password)
- User pelanggan demo
- Billing plans (Trial, Basic, Premium)
- Data RHK dan Jenis RHK

### 6. Build Assets
```bash
npm run build
```

### 7. Jalankan Development Server
```bash
# Opsi 1: Manual
php artisan serve
npm run dev

# Opsi 2: Menggunakan composer script (recommended)
composer run dev
# Ini akan menjalankan server, queue, dan vite secara bersamaan
```

Aplikasi akan berjalan di `http://localhost:8000`

## 🌐 Deployment ke VPS Ubuntu

### Langkah 1: Persiapan Server

#### 1.1 Update System
```bash
sudo apt update && sudo apt upgrade -y
```

#### 1.2 Install PHP 8.4
```bash
# Add PHP repository
sudo add-apt-repository ppa:ondrej/php -y
sudo apt update

# Install PHP 8.4 dan extensions
sudo apt install -y php8.4 php8.4-fpm php8.4-cli php8.4-common \
    php8.4-mysql php8.4-xml php8.4-curl php8.4-gd php8.4-mbstring \
    php8.4-zip php8.4-bcmath php8.4-intl php8.4-readline
```

#### 1.3 Install Composer
```bash
curl -sS https://getcomposer.org/installer | php
sudo mv composer.phar /usr/local/bin/composer
composer --version
```

#### 1.4 Install MySQL
```bash
sudo apt install -y mysql-server
sudo mysql_secure_installation
```

Buat database:
```bash
sudo mysql -u root -p
```
```sql
CREATE DATABASE laporan_asn CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'systemasn26'@'localhost' IDENTIFIED BY 'System@SN2026';
GRANT ALL PRIVILEGES ON laporan_asn.* TO 'systemasn26'@'localhost';
FLUSH PRIVILEGES;
EXIT;
```

#### 1.5 Install Nginx
```bash
sudo apt install -y nginx
```

#### 1.6 Install Node.js
```bash
curl -fsSL https://deb.nodesource.com/setup_20.x | sudo -E bash -
sudo apt install -y nodejs
node --version
npm --version
```

#### 1.7 Install Supervisor (untuk Queue)
```bash
sudo apt install -y supervisor
```

### Langkah 2: Deploy Aplikasi

#### 2.1 Clone Repository
```bash
# Buat direktori untuk aplikasi
sudo mkdir -p /var/www/laporan-asn
sudo chown -R $USER:$USER /var/www/laporan-asn

# Clone repository
cd /var/www
git clone <repository-url> laporan-asn
cd laporan-asn
```

#### 2.2 Install Dependencies
```bash
# Install PHP dependencies
composer install --optimize-autoloader --no-dev

# Install Node dependencies dan build assets
npm install
npm run build
```

#### 2.3 Setup Environment
```bash
cp .env.example .env
php artisan key:generate
```

Edit `.env` untuk production:
```env
APP_NAME="Laporan ASN"
APP_ENV=production
APP_DEBUG=false
APP_URL=https://yourdomain.com

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=laporan_asn
DB_USERNAME=laporan_user
DB_PASSWORD=password_kuat_anda

QUEUE_CONNECTION=database
# Atau gunakan redis untuk performa lebih baik:
# QUEUE_CONNECTION=redis

MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=your-email@gmail.com
MAIL_PASSWORD=your-app-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS=noreply@yourdomain.com
MAIL_FROM_NAME="${APP_NAME}"
```

#### 2.4 Setup Permissions
```bash
sudo chown -R www-data:www-data /var/www/laporan-asn
sudo chmod -R 755 /var/www/laporan-asn
sudo chmod -R 775 /var/www/laporan-asn/storage
sudo chmod -R 775 /var/www/laporan-asn/bootstrap/cache
```

#### 2.5 Migrasi Database
```bash
php artisan migrate --force
php artisan db:seed --force
```

#### 2.6 Optimize Laravel
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache
```

### Langkah 3: Konfigurasi Nginx

#### 3.1 Buat Konfigurasi Site
```bash
sudo nano /etc/nginx/sites-available/toolrhk.web.id
```

Paste konfigurasi berikut:
```nginx
server {
    listen 80;
    listen [::]:80;
    server_name toolrhk.web.id www.toolrhk.web.id;
    root /var/www/laporan-asn/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.4-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
        fastcgi_hide_header X-Powered-By;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }

    # Increase upload size
    client_max_body_size 20M;
}
```

#### 3.2 Enable Site
```bash
sudo ln -s /etc/nginx/sites-available/toolrhk.web.id /etc/nginx/sites-enabled/
sudo nginx -t
sudo systemctl restart nginx
```

### Langkah 4: Setup SSL dengan Let's Encrypt

```bash
# Install Certbot
sudo apt install -y certbot python3-certbot-nginx

# Dapatkan SSL certificate
sudo certbot --nginx -d toolrhk.my.id -d www.toolrhk.my.id

# Auto-renewal sudah disetup otomatis, test dengan:
sudo certbot renew --dry-run
```

## ⚙️ Konfigurasi Queue & Schedule

### Setup Queue Worker dengan Supervisor

#### 1. Buat Konfigurasi Supervisor
```bash
sudo nano /etc/supervisor/conf.d/laporan-asn-worker.conf
```

Paste konfigurasi:
```ini
[program:laporan-asn-worker]
process_name=%(program_name)s_%(process_num)02d
command=php /var/www/laporan-asn/artisan queue:work database --sleep=3 --tries=3 --max-time=3600
autostart=true
autorestart=true
stopasgroup=true
killasgroup=true
user=www-data
numprocs=2
redirect_stderr=true
stdout_logfile=/var/www/laporan-asn/storage/logs/worker.log
stopwaitsecs=3600
```

#### 2. Start Supervisor
```bash
sudo supervisorctl reread
sudo supervisorctl update
sudo supervisorctl start laporan-asn-worker:*
```

#### 3. Monitoring Queue Worker
```bash
# Check status
sudo supervisorctl status laporan-asn-worker:*

# Restart worker (setelah deploy)
sudo supervisorctl restart laporan-asn-worker:*

# Stop worker
sudo supervisorctl stop laporan-asn-worker:*

# View logs
tail -f /var/www/laporan-asn/storage/logs/worker.log
```

### Setup Laravel Scheduler dengan Cron

#### 1. Edit Crontab
```bash
sudo crontab -e -u www-data
```

#### 2. Tambahkan Cron Entry
```cron
* * * * * cd /var/www/laporan-asn && php artisan schedule:run >> /dev/null 2>&1
```

Ini akan menjalankan Laravel scheduler setiap menit.

#### 3. Verifikasi Cron
```bash
# List cron jobs
sudo crontab -l -u www-data

# Monitor cron logs
sudo tail -f /var/log/syslog | grep CRON
```

### Scheduled Tasks yang Tersedia

Aplikasi ini memiliki command untuk assign trial ke user existing:
```bash
php artisan trial:assign-existing
```

Untuk menambahkan ke scheduler, edit `routes/console.php`:
```php
use Illuminate\Support\Facades\Schedule;

Schedule::command('trial:assign-existing')
    ->daily()
    ->at('01:00');

// Contoh task lain yang mungkin diperlukan:
Schedule::command('subscription:check-expired')
    ->hourly();

Schedule::command('backup:run')
    ->daily()
    ->at('02:00');
```

## 📖 Penggunaan

### Login Credentials (Setelah Seeding)

**Superadmin:**
- Email: admin@example.com
- Password: password

**Pelanggan Demo:**
- Email: pelanggan@example.com
- Password: password

### Workflow Superadmin

1. Login sebagai superadmin
2. Kelola RHK dan Jenis RHK di menu Admin
3. Kelola Billing Plans
4. Kelola User dan Subscription
5. Monitor dashboard untuk statistik

### Workflow Pelanggan

1. Register atau login
2. Cek status subscription di dashboard
3. Buat laporan baru (pilih RHK dan Jenis RHK)
4. Isi form laporan lengkap
5. Upload foto dokumentasi
6. Download laporan dalam format PDF atau DOCX

### Artisan Commands

```bash
# Assign trial ke user yang belum punya subscription
php artisan trial:assign-existing

# Clear cache
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Optimize untuk production
php artisan optimize

# Run queue worker (development)
php artisan queue:work

# Run scheduler (development)
php artisan schedule:work
```

## 🔧 Troubleshooting

### Permission Issues
```bash
sudo chown -R www-data:www-data /var/www/laporan-asn
sudo chmod -R 755 /var/www/laporan-asn
sudo chmod -R 775 /var/www/laporan-asn/storage
sudo chmod -R 775 /var/www/laporan-asn/bootstrap/cache
```

### Queue Not Processing
```bash
# Restart queue worker
sudo supervisorctl restart laporan-asn-worker:*

# Check worker logs
tail -f /var/www/laporan-asn/storage/logs/worker.log

# Check failed jobs
php artisan queue:failed

# Retry failed jobs
php artisan queue:retry all
```

### Scheduler Not Running
```bash
# Check cron is running
sudo systemctl status cron

# Check cron logs
sudo tail -f /var/log/syslog | grep CRON

# Test scheduler manually
php artisan schedule:run
```

### 500 Error After Deploy
```bash
# Clear all cache
php artisan optimize:clear

# Regenerate cache
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Check logs
tail -f /var/www/laporan-asn/storage/logs/laravel.log
```

### Database Connection Error
```bash
# Test database connection
php artisan tinker
>>> DB::connection()->getPdo();

# Check MySQL is running
sudo systemctl status mysql

# Check credentials in .env
cat .env | grep DB_
```

### Upload File Issues
```bash
# Check storage link
php artisan storage:link

# Check permissions
sudo chmod -R 775 /var/www/laporan-asn/storage/app/public

# Check upload size in php.ini
sudo nano /etc/php/8.4/fpm/php.ini
# Set: upload_max_filesize = 20M
# Set: post_max_size = 20M

sudo systemctl restart php8.4-fpm
```

## 🔄 Update Aplikasi (Setelah Git Pull)

```bash
cd /var/www/laporan-asn

# Pull latest code
git pull origin main

# Update dependencies
composer install --optimize-autoloader --no-dev
npm install
npm run build

# Run migrations
php artisan migrate --force

# Clear and rebuild cache
php artisan optimize:clear
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan event:cache

# Restart queue workers
sudo supervisorctl restart laporan-asn-worker:*

# Fix permissions
sudo chown -R www-data:www-data /var/www/laporan-asn
sudo chmod -R 775 /var/www/laporan-asn/storage
sudo chmod -R 775 /var/www/laporan-asn/bootstrap/cache
```

## 📝 Maintenance Mode

```bash
# Enable maintenance mode
php artisan down --secret="maintenance-bypass-token"

# Access site during maintenance:
# https://yourdomain.com/maintenance-bypass-token

# Disable maintenance mode
php artisan up
```

## 🔐 Security Checklist

- [ ] Set `APP_DEBUG=false` di production
- [ ] Set `APP_ENV=production`
- [ ] Gunakan password database yang kuat
- [ ] Setup SSL certificate
- [ ] Setup firewall (UFW)
- [ ] Disable directory listing di Nginx
- [ ] Regular backup database
- [ ] Update dependencies secara berkala
- [ ] Monitor logs untuk suspicious activity

## 📊 Monitoring & Logs

```bash
# Application logs
tail -f /var/www/laporan-asn/storage/logs/laravel.log

# Queue worker logs
tail -f /var/www/laporan-asn/storage/logs/worker.log

# Nginx access logs
sudo tail -f /var/log/nginx/access.log

# Nginx error logs
sudo tail -f /var/log/nginx/error.log

# PHP-FPM logs
sudo tail -f /var/log/php8.4-fpm.log
```

## 🤝 Contributing

Untuk berkontribusi pada project ini:

1. Fork repository
2. Buat branch fitur (`git checkout -b feature/AmazingFeature`)
3. Commit perubahan (`git commit -m 'Add some AmazingFeature'`)
4. Push ke branch (`git push origin feature/AmazingFeature`)
5. Buat Pull Request

## 📄 License

Project ini menggunakan [MIT License](https://opensource.org/licenses/MIT).

## 👥 Support

Untuk bantuan dan pertanyaan:
- Email: support@yourdomain.com
- Documentation: https://yourdomain.com/docs

---

**Dibuat dengan ❤️ menggunakan Laravel 13**
