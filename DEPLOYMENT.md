# DEPLOYMENT.md

> Dokumentasi deployment production dan test suite untuk Sistem Informasi Manajemen Pelatihan Bapeltan Jawa Tengah.

---

## Table of Contents

1. [Deploy Production — Shared Hosting (cPanel/Plesk)](#1-deploy-production--shared-hosting-cpanelplesk)
2. [Test Suite — Comprehensive](#2-test-suite--comprehensive)
3. [File Summary](#3-file-summary)

---

## 1. Deploy Production — Shared Hosting (cPanel/Plesk)

### 1.1 Prerequisites

| Komponen | Minimum Version | Keterangan |
|----------|----------------|------------|
| PHP | 8.2+ | Cek via phpinfo di cPanel |
| MySQL | 8.0+ / MariaDB 10.3+ | Pastikan support UUID |
| Composer | 2.x | Untuk install dependencies |
| Node.js | 18+ | Untuk build assets (Vite) |
| OpenSSL | Terpasang | Untuk APP_KEY generation |
| PHP Extensions | pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, fileinfo, gd | Cek di phpinfo |

**PHP Extensions wajib (biasanya sudah ada):**
```
pdo_mysql, mbstring, openssl, tokenizer, xml, ctype, json, bcmath, fileinfo, gd, curl, zip
```

### 1.2 Upload Project

**Option A: File Manager (Manual)**
1. Login cPanel → File Manager
2. Navigate ke `public_html/` (atau subdomain folder)
3. Upload semua file project (kecuali `node_modules/`, `.git/`, `tests/`)
4. Extract jika dalam bentuk ZIP

**Option B: Git (Jika tersedia)**
```bash
cd /home/username/public_html
git clone https://github.com/bapeltan/bapeltan.git .
```

**File yang TIDAK perlu di-upload ke production:**
```
node_modules/
.git/
tests/
.opencode/
*.html (debug files)
*.txt (cookie/headers debug)
*.csv (test data)
.editorconfig
phpunit.xml
phpunit.xml.dist
```

### 1.3 Environment Configuration

Copy `.env.example` ke `.env` dan edit dengan values production:

```env
APP_NAME="Bapeltan Jateng"
APP_ENV=production
APP_KEY=
APP_DEBUG=false
APP_URL=https://YOUR_DOMAIN.COM

APP_LOCALE=id
APP_FALLBACK_LOCALE=id
APP_FAKER_LOCALE=id_ID

ADMIN_PASSWORD=YOUR_SECURE_ADMIN_PASSWORD

APP_MAINTENANCE_DRIVER=file

PHP_CLI_SERVER_WORKERS=4

BCRYPT_ROUNDS=12

LOG_CHANNEL=stack
LOG_STACK=single
LOG_DEPRECATIONS_CHANNEL=null
LOG_LEVEL=error

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=YOUR_DB_NAME
DB_USERNAME=YOUR_DB_USER
DB_PASSWORD=YOUR_DB_PASSWORD

SESSION_DRIVER=file
SESSION_LIFETIME=120
SESSION_ENCRYPT=false
SESSION_PATH=/
SESSION_DOMAIN=null
SESSION_SECURE_COOKIE=true
SESSION_HTTP_ONLY=true

BROADCAST_CONNECTION=log
FILESYSTEM_DISK=local
QUEUE_CONNECTION=database

CACHE_STORE=database

MAIL_MAILER=smtp
MAIL_HOST=smtp.mailprovider.com
MAIL_PORT=587
MAIL_USERNAME=your-email@domain.com
MAIL_PASSWORD=your-email-password
MAIL_ENCRYPTION=tls
MAIL_FROM_ADDRESS="noreply@YOUR_DOMAIN.COM"
MAIL_FROM_NAME="${APP_NAME}"

VITE_APP_NAME="${APP_NAME}"
```

**PENTING:**
- `SESSION_DRIVER=file` — JANGAN pakai `database` karena UUID primary keys di tabel `users` pecah di MySQL sessions table
- `APP_DEBUG=false` — Jangan tampilkan error detail di production
- `SESSION_SECURE_COOKIE=true` — Paksa HTTPS untuk cookies
- `LOG_LEVEL=error` — Hanya log error, bukan debug

### 1.4 Install Dependencies

```bash
# PHP dependencies (production only, tanpa dev dependencies)
composer install --no-dev --optimize-autoloader

# Node.js dependencies & build assets
npm install
npm run build
```

**Jika Composer tidak tersedia di server:**
1. Jalankan `composer install` di local machine
2. Upload folder `vendor/` ke server
3. Atau gunakan `composer.phar`:
```bash
php composer.phar install --no-dev --optimize-autoloader
```

### 1.5 Laravel Setup

```bash
# Generate application key
php artisan key:generate

# Run migrations
php artisan migrate --force

# Seed database (opsional — untuk data awal)
php artisan db:seed

# Cache configurations
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

**Jika CLI artisan tidak tersedia di cPanel:**
- Gunakan "Terminal" di cPanel (jika tersedia)
- Atau jalankan via SSH:
```bash
ssh username@your-server
cd /home/username/public_html
php artisan key:generate
php artisan migrate --force
php artisan db:seed
php artisan config:cache
php artisan route:cache
php artisan view:cache
php artisan optimize
```

### 1.6 Folder Permissions

Pastikan folder berikut writable oleh web server:

```bash
chmod -R 755 storage/
chmod -R 755 bootstrap/cache/
chmod 644 .env
chmod 644 composer.json
chmod 644 composer.lock
```

**Jika permission masih bermasalah:**
```bash
chown -R username:username storage/
chown -R username:username bootstrap/cache/
```

### 1.7 Document Root Configuration

**PENTING:** Document root harus指向 `public/` folder, bukan root project.

**Cara 1: cPanel Addon Domain / Subdomain**
- Document Root: `/home/username/public_html/public`

**Cara 2: .htaccess di root (jika tidak bisa ubah document root)**
Buat `.htaccess` di root project:
```apache
<IfModule mod_rewrite.c>
    RewriteEngine On
    RewriteRule ^(.*)$ public/$1 [L]
</IfModule>
```

### 1.8 HTTPS & Security

**Setup SSL (Let's Encrypt via cPanel):**
1. cPanel → SSL/TLS Status
2. Pilih domain → Issue/Install

**Force HTTPS — tambah di `public/.htaccess` (sebelum bagian RewriteEngine):**
```apache
RewriteEngine On
RewriteCond %{HTTPS} off
RewriteRule ^(.*)$ https://%{HTTP_HOST}%{REQUEST_URI} [L,R=301]
```

**Security Headers — tambah di `public/.htaccess`:**
```apache
<IfModule mod_headers.c>
    Header set X-Content-Type-Options "nosniff"
    Header set X-Frame-Options "SAMEORIGIN"
    Header set X-XSS-Protection "1; mode=block"
    Header set Referrer-Policy "strict-origin-when-cross-origin"
    Header set Content-Security-Policy "upgrade-insecure-requests"
</IfModule>
```

### 1.9 Cron / Scheduler

**Setup cron job di cPanel → Cron Jobs:**

```
* * * * * cd /home/username/public_html && php artisan schedule:run >> /dev/null 2>&1
```

**Catatan:** Jika shared hosting tidak support cron, fitur email notification (queue) tidak akan jalan otomatis. Alternatif:
- Gunakan `QUEUE_CONNECTION=sync` (email langsung terkirim, tanpa queue)
- Atau gunakan external cron service (cron-job.org, easy-cron.com)

### 1.10 Queue Worker (Opsional)

Shared hosting biasanya TIDAK support queue worker. Jika tetap ingin pakai queue:

**Option 1: Database Queue + Scheduler**
```env
QUEUE_CONNECTION=database
```
Lalu tambah cron job:
```
* * * * * cd /home/username/public_html && php artisan queue:work --stop-when-empty >> /dev/null 2>&1
```

**Option 2: Sync Queue (Recommended untuk shared hosting)**
```env
QUEUE_CONNECTION=sync
```
Email akan dikirim langsung tanpa delay. Untuk volume tinggi, ini sebenarnya lebih reliable di shared hosting.

### 1.11 Post-Deploy Checklist

```
[ ] 1. Login admin panel (/admin) berhasil
     - Email: bapeltan@gmail.com
     - Password: (dari ADMIN_PASSWORD di .env)

[ ] 2. Data kabupaten sudah ter-seed (30 kabupaten Jawa Tengah)

[ ] 3. Form registrasi publik (/register) berfungsi
     - Coba registrasi dengan NIK test
     - User otomatis terbuat

[ ] 4. API endpoints berfungsi:
     - GET /api/cek-nik?nik=...
     - POST /api/daftar-pelatihan
     - GET /api/kegiatan
     - GET /api/artikel

[ ] 5. Sertifikat PDF download/preview berfungsi
     - Buat registrasi dengan status "selesai"
     - Test download di /sertifikat/{id}/download

[ ] 6. Email notifikasi terkirim (cek spam folder)

[ ] 7. Role-based access berfungsi:
     - Guest tidak bisa akses /admin
     - Peserta tidak bisa akses /admin
     - Admin bisa akses semua menu

[ ] 8. Filament resources berfungsi:
     - Peserta, Kegiatan, Registrasi, Evaluasi, dll
     - Export CSV berfungsi
     - Laporan/statistik berfungsi

[ ] 9. Asset loading benar:
     - CSS (Tailwind + DaisyUI) ter-load
     - JavaScript (Livewire) berfungsi
     - Gambar/logo partner ter-load

[ ] 10. Error handling:
      - 404 page tampil jika route tidak ada
      - 500 page tampil jika server error
      - Error log tersimpan di storage/logs/
```

### 1.12 Troubleshooting

| Masalah | Solusi |
|---------|--------|
| White screen / 500 error | Cek `storage/logs/laravel.log` |
| APP_KEY not set | Jalankan `php artisan key:generate` |
| Database connection failed | Cek kredensial di `.env` |
| Session not working | Pastikan `SESSION_DRIVER=file`, bukan `database` |
| Assets not loading | Jalankan `npm run build`, cek `public/build/` ada |
| Permission denied | Set `storage/` dan `bootstrap/cache/` ke 755 |
| CSRF token mismatch | Clear cache: `php artisan config:clear && php artisan cache:clear` |
| Route not found | Pastikan document root指向 `public/`, jalankan `php artisan route:cache` |

---

## 2. Test Suite — Comprehensive

### 2.1 Infrastructure

| Komponen | Value | Keterangan |
|----------|-------|------------|
| Framework | PHPUnit 11.5 | Sudah terpasang via composer.json |
| Database | SQLite in-memory | Dikonfigurasi di phpunit.xml |
| Trait | RefreshDatabase | Auto migrate & rollback per test |
| Run command | `php artisan test` | Atau `vendor/bin/phpunit` |

**phpunit.xml sudah dikonfigurasi dengan:**
```xml
<env name="DB_CONNECTION" value="sqlite"/>
<env name="DB_DATABASE" value=":memory:"/>
<env name="APP_ENV" value="testing"/>
<env name="BCRYPT_ROUNDS" value="4"/>
<env name="CACHE_STORE" value="array"/>
<env name="MAIL_MAILER" value="array"/>
<env name="QUEUE_CONNECTION" value="sync"/>
<env name="SESSION_DRIVER" value="array"/>
```

### 2.2 Model Factories

#### 2.2.1 Update UserFactory

**Path:** `database/factories/UserFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class UserFactory extends Factory
{
    protected static ?string $password;

    public function definition(): array
    {
        return [
            'name' => fake()->name(),
            'email' => fake()->unique()->safeEmail(),
            'email_verified_at' => now(),
            'password' => static::$password ??= Hash::make('password'),
            'remember_token' => Str::random(10),
            'role' => 'peserta',
        ];
    }

    public function admin(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'admin',
        ]);
    }

    public function peserta(): static
    {
        return $this->state(fn (array $attributes) => [
            'role' => 'peserta',
        ]);
    }

    public function unverified(): static
    {
        return $this->state(fn (array $attributes) => [
            'email_verified_at' => null,
        ]);
    }
}
```

**Tambahkan `HasFactory` ke model User jika belum ada:**
```php
// app/Models/User.php — pastikan ada:
use Illuminate\Database\Eloquent\Factories\HasFactory;
class User extends Authenticatable
{
    use HasFactory, Notifiable;
    // ...
}
```

#### 2.2.2 KabupatenFactory

**Path:** `database/factories/KabupatenFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Kabupaten;
use Illuminate\Database\Eloquent\Factories\Factory;

class KabupatenFactory extends Factory
{
    protected $model = Kabupaten::class;

    public function definition(): array
    {
        $kode = fake()->unique()->numerify('330#');
        return [
            'id' => strtolower(fake()->unique()->bothify('??-####')),
            'kode' => $kode,
            'nama' => fake()->city(),
        ];
    }

    public function temanggung(): static
    {
        return $this->state(fn (array $attributes) => [
            'id' => 'kab-temanggung',
            'kode' => '3323',
            'nama' => 'Temanggung',
        ]);
    }
}
```

#### 2.2.3 KegiatanTypeFactory

**Path:** `database/factories/KegiatanTypeFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\KegiatanType;
use Illuminate\Database\Eloquent\Factories\Factory;

class KegiatanTypeFactory extends Factory
{
    protected $model = KegiatanType::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'nama_type' => fake()->randomElement([
                'Agribisnis Tanaman Pangan',
                'Agribisnis Perkebunan',
                'Mekanisasi Pertanian',
            ]),
        ];
    }

    public function tanamanPangan(): static
    {
        return $this->state(fn (array $attributes) => [
            'id' => 'type-pangan',
            'nama_type' => 'Agribisnis Tanaman Pangan',
        ]);
    }
}
```

#### 2.2.4 KegiatanFactory

**Path:** `database/factories/KegiatanFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\KegiatanType;
use Illuminate\Database\Eloquent\Factories\Factory;

class KegiatanFactory extends Factory
{
    protected $model = Kegiatan::class;

    public function definition(): array
    {
        $mulai = fake()->dateTimeBetween('+1 month', '+3 month');
        $selesai = (clone $modify)->modify('+' . rand(3, 10) . ' days');

        return [
            'id' => $this->faker->uuid,
            'kegiatan_type_id' => KegiatanType::factory(),
            'nama_pelatihan' => fake()->sentence(3),
            'kode_pelatihan' => strtoupper(fake()->bothify('???-##')),
            'deskripsi' => fake()->paragraph(),
            'tanggal_mulai' => $mulai->format('Y-m-d'),
            'tanggal_selesai' => $selesai->format('Y-m-d'),
            'kuota' => fake()->randomElement([20, 30, 50]),
            'status' => 'active',
        ];
    }

    public function inactive(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'inactive',
        ]);
    }

    public function full(): static
    {
        return $this->state(fn (array $attributes) => [
            'kuota' => 0,
        ]);
    }
}
```

#### 2.2.5 PesertaFactory

**Path:** `database/factories/PesertaFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Kabupaten;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Database\Eloquent\Factories\Factory;

class PesertaFactory extends Factory
{
    protected $model = Peserta::class;

    public function definition(): array
    {
        $nik = fake()->unique()->numerify('330101010101####');
        $nama = fake()->name();
        $email = strtolower(str_replace(' ', '.', $nama)) . '@test.com';

        return [
            'nik' => $nik,
            'user_id' => User::factory()->peserta(),
            'kabupaten_id' => Kabupaten::factory(),
            'nama' => strtoupper($nama),
            'tempat_lahir' => fake()->city(),
            'tanggal_lahir' => fake()->dateTimeBetween('-50 years', '-18 years')->format('Y-m-d'),
            'nomor_telepon' => fake()->numerify('08##########'),
            'agama' => fake()->randomElement(['ISLAM', 'KRISTEN', 'KATOLIK', 'HINDU', 'BUDDHA']),
            'jenis_kelamin' => fake()->randomElement(['LAKI-LAKI', 'PEREMPUAN']),
            'status_pernikahan' => fake()->randomElement(['BELUM MENIKAH', 'MENIKAH', 'CERAI']),
            'pendidikan_terakhir' => fake()->randomElement(['SD', 'SMP', 'SMA', 'D3', 'S1', 'S2']),
            'pekerjaan' => fake()->randomElement(['Petani', 'Buruh Tani', 'Wiraswasta', 'PNS']),
            'usaha_tani' => fake()->randomElement(['Padi', 'Jagung', 'Kedelai', 'Sayuran', 'Buah-buahan']),
            'email' => $email,
            'alamat_lengkap' => fake()->address(),
            'nama_poktan' => 'Poktan ' . fake()->city(),
            'alamat_poktan' => fake()->address(),
            'nip' => null,
        ];
    }

    public function withNik(string $nik): static
    {
        return $this->state(fn (array $attributes) => [
            'nik' => $nik,
        ]);
    }
}
```

#### 2.2.6 RegistrasiUlangFactory

**Path:** `database/factories/RegistrasiUlangFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Illuminate\Database\Eloquent\Factories\Factory;

class RegistrasiUlangFactory extends Factory
{
    protected $model = RegistrasiUlang::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'peserta_nik' => Peserta::factory(),
            'kegiatan_id' => Kegiatan::factory(),
            'kegiatan_type_id' => null,
            'tahun' => (int) date('Y'),
            'status' => 'pending',
            'bersedia' => false,
            'sertifikat' => null,
        ];
    }

    public function pending(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'pending',
        ]);
    }

    public function diterima(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'diterima',
        ]);
    }

    public function ditolak(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'ditolak',
        ]);
    }

    public function bersedia(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'bersedia',
            'bersedia' => true,
        ]);
    }

    public function selesai(): static
    {
        return $this->state(fn (array $attributes) => [
            'status' => 'selesai',
            'sertifikat' => 'sertifikat-' . fake()->uuid . '.pdf',
        ]);
    }
}
```

#### 2.2.7 EvaluasiFactory

**Path:** `database/factories/EvaluasiFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\Evaluasi;
use App\Models\EvaluasiType;
use App\Models\Kegiatan;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluasiFactory extends Factory
{
    protected $model = Evaluasi::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'kegiatan_id' => Kegiatan::factory(),
            'evaluasi_type_id' => EvaluasiType::factory(),
            'judul' => fake()->sentence(3),
            'deskripsi' => fake()->paragraph(),
        ];
    }
}
```

#### 2.2.8 EvaluasiTypeFactory

**Path:** `database/factories/EvaluasiTypeFactory.php`

```php
<?php

namespace Database\Factories;

use App\Models\EvaluasiType;
use Illuminate\Database\Eloquent\Factories\Factory;

class EvaluasiTypeFactory extends Factory
{
    protected $model = EvaluasiType::class;

    public function definition(): array
    {
        return [
            'id' => $this->faker->uuid,
            'nama' => fake()->randomElement(['Pre-Test', 'Post-Test', 'Evaluasi Akhir']),
        ];
    }

    public function preTest(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'Pre-Test',
        ]);
    }

    public function postTest(): static
    {
        return $this->state(fn (array $attributes) => [
            'nama' => 'Post-Test',
        ]);
    }
}
```

### 2.3 Feature Tests

#### 2.3.1 RegistrationFlowTest.php

**Path:** `tests/Feature/RegistrationFlowTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrationFlowTest extends TestCase
{
    use RefreshDatabase;

    private array $validData;
    private Kabupaten $kabupaten;

    protected function setUp(): void
    {
        parent::setUp();

        $this->kabupaten = Kabupaten::factory()->create();
        $this->validData = [
            'kabupaten_id' => $this->kabupaten->id,
            'nik' => '3301010101010001',
            'nama' => 'Test Petani',
            'tempat_lahir' => 'Temanggung',
            'tanggal_lahir' => '1990-01-01',
            'jenis_kelamin' => 'LAKI-LAKI',
            'nomor_telepon' => '081234567890',
            'agama' => 'ISLAM',
            'status_pernikahan' => 'BELUM MENIKAH',
            'pendidikan_terakhir' => 'SMA',
            'pekerjaan' => 'Petani',
            'usaha_tani' => 'Padi',
            'email' => 'petani@test.com',
            'alamat_lengkap' => 'Jl. Pertanian No. 1',
            'nama_poktan' => 'Poktan Makmur',
            'alamat_poktan' => 'Jl. Poktan No. 1',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ];
    }

    public function test_registration_page_is_displayed(): void
    {
        $response = $this->get(route('public.registration'));

        $response->assertStatus(200);
    }

    public function test_registration_succeeds_with_valid_data(): void
    {
        $response = $this->post(route('public.registration.store'), $this->validData);

        $response->assertRedirect();
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('pesertas', [
            'nik' => '3301010101010001',
            'nama' => 'TEST PETANI',
        ]);

        $this->assertDatabaseHas('users', [
            'email' => 'petani@test.com',
            'role' => 'peserta',
        ]);
    }

    public function test_registration_fails_with_duplicate_nik(): void
    {
        Peserta::factory()->create(['nik' => '3301010101010001']);

        $response = $this->post(route('public.registration.store'), $this->validData);

        $response->assertSessionHasErrors('nik');
    }

    public function test_registration_fails_with_invalid_nik_format(): void
    {
        $this->validData['nik'] = '12345'; // bukan 16 digit

        $response = $this->post(route('public.registration.store'), $this->validData);

        $response->assertSessionHasErrors('nik');
    }

    public function test_registration_fails_when_underage(): void
    {
        $this->validData['tanggal_lahir'] = now()->subYears(15)->format('Y-m-d');

        $response = $this->post(route('public.registration.store'), $this->validData);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_registration_fails_when_overage(): void
    {
        $this->validData['tanggal_lahir'] = now()->subYears(55)->format('Y-m-d');

        $response = $this->post(route('public.registration.store'), $this->validData);

        $response->assertSessionHasErrors('tanggal_lahir');
    }

    public function test_registration_auto_creates_user(): void
    {
        $this->post(route('public.registration.store'), $this->validData);

        $user = User::where('email', 'petani@test.com')->first();

        $this->assertNotNull($user);
        $this->assertEquals('peserta', $user->role);

        $peserta = Peserta::where('nik', '3301010101010001')->first();
        $this->assertEquals($user->id, $peserta->user_id);
    }

    public function test_registration_reuses_existing_user(): void
    {
        $user = User::factory()->create([
            'email' => 'petani@test.com',
            'role' => 'peserta',
        ]);

        $this->post(route('public.registration.store'), $this->validData);

        $peserta = Peserta::where('nik', '3301010101010001')->first();
        $this->assertEquals($user->id, $peserta->user_id);
        $this->assertEquals(1, User::where('email', 'petani@test.com')->count());
    }

    public function test_registration_validates_all_required_fields(): void
    {
        $response = $this->post(route('public.registration.store'), []);

        $response->assertSessionHasErrors([
            'kabupaten_id', 'nik', 'nama', 'tempat_lahir', 'tanggal_lahir',
            'jenis_kelamin', 'nomor_telepon', 'agama', 'status_pernikahan',
            'pendidikan_terakhir', 'pekerjaan', 'usaha_tani', 'email',
            'alamat_lengkap', 'nama_poktan', 'alamat_poktan', 'password',
        ]);
    }
}
```

#### 2.3.2 CertificateTest.php

**Path:** `tests/Feature/CertificateTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Storage;
use Tests\TestCase;

class CertificateTest extends TestCase
{
    use RefreshDatabase;

    private RegistrasiUlang $registrasi;
    private User $user;

    protected function setUp(): void
    {
        parent::setUp();

        $this->user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create([
            'user_id' => $this->user->id,
            'nik' => '3301010101010001',
        ]);
        $kegiatan = Kegiatan::factory()->create();

        $this->registrasi = RegistrasiUlang::factory()->selesai()->create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatan->id,
        ]);
    }

    public function test_download_certificate_returns_pdf(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('sertifikat.download', $this->registrasi->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'application/pdf');
    }

    public function test_preview_certificate_returns_stream(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('sertifikat.preview', $this->registrasi->id));

        $response->assertStatus(200);
        $response->assertHeader('content-type', 'text/html; charset=UTF-8');
    }

    public function test_certificate_only_accessible_by_owner(): void
    {
        $otherUser = User::factory()->peserta()->create();

        $response = $this->actingAs($otherUser)
            ->get(route('sertifikat.download', $this->registrasi->id));

        $response->assertStatus(403);
    }

    public function test_certificate_404_when_registration_not_selesai(): void
    {
        $this->registrasi->update(['status' => 'pending']);

        $response = $this->actingAs($this->user)
            ->get(route('sertifikat.download', $this->registrasi->id));

        $response->assertStatus(404);
    }

    public function test_certificate_404_for_invalid_id(): void
    {
        $response = $this->actingAs($this->user)
            ->get(route('sertifikat.download', 'non-existent-id'));

        $response->assertStatus(404);
    }

    public function test_unauthenticated_user_cannot_access_certificate(): void
    {
        $response = $this->get(route('sertifikat.download', $this->registrasi->id));

        $response->assertRedirect();
    }
}
```

#### 2.3.3 EvaluasiTest.php

**Path:** `tests/Feature/EvaluasiTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Evaluasi;
use App\Models\EvaluasiQuestion;
use App\Models\EvaluasiQuestionOption;
use App\Models\EvaluasiResponse;
use App\Models\EvaluasiType;
use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class EvaluasiTest extends TestCase
{
    use RefreshDatabase;

    private User $pesertaUser;
    private Peserta $peserta;
    private Kegiatan $kegiatan;
    private Evaluasi $evaluasi;

    protected function setUp(): void
    {
        parent::setUp();

        $this->pesertaUser = User::factory()->peserta()->create();
        $this->peserta = Peserta::factory()->create([
            'user_id' => $this->pesertaUser->id,
            'nik' => '3301010101010001',
        ]);
        $this->kegiatan = Kegiatan::factory()->create();

        $evaluasiType = EvaluasiType::factory()->preTest()->create();
        $this->evaluasi = Evaluasi::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'evaluasi_type_id' => $evaluasiType->id,
        ]);
    }

    public function test_peserta_can_access_evaluasi_page(): void
    {
        $response = $this->actingAs($this->pesertaUser)
            ->get(route('peserta.evaluasi.index'));

        $response->assertStatus(200);
    }

    public function test_submit_evaluasi_with_radio_answer(): void
    {
        $question = EvaluasiQuestion::factory()->create([
            'evaluasi_id' => $this->evaluasi->id,
            'tipe_jawaban' => 'radio',
        ]);

        $option = EvaluasiQuestionOption::factory()->create([
            'question_id' => $question->id,
        ]);

        $response = $this->actingAs($this->pesertaUser)
            ->post(route('peserta.evaluasi.submit', $this->evaluasi->id), [
                'answers' => [
                    $question->id => $option->id,
                ],
            ]);

        $response->assertRedirect();
        $this->assertDatabaseHas('evaluasi_responses', [
            'evaluasi_id' => $this->evaluasi->id,
            'peserta_nik' => '3301010101010001',
        ]);
    }

    public function test_submit_evaluasi_with_text_answer(): void
    {
        $question = EvaluasiQuestion::factory()->create([
            'evaluasi_id' => $this->evaluasi->id,
            'tipe_jawaban' => 'text',
        ]);

        $response = $this->actingAs($this->pesertaUser)
            ->post(route('peserta.evaluasi.submit', $this->evaluasi->id), [
                'answers' => [
                    $question->id => 'Jawaban teks saya',
                ],
            ]);

        $response->assertRedirect();
    }

    public function test_submit_evaluasi_with_scale_answer(): void
    {
        $question = EvaluasiQuestion::factory()->create([
            'evaluasi_id' => $this->evaluasi->id,
            'tipe_jawaban' => 'scale',
        ]);

        $response = $this->actingAs($this->pesertaUser)
            ->post(route('peserta.evaluasi.submit', $this->evaluasi->id), [
                'answers' => [
                    $question->id => 8,
                ],
            ]);

        $response->assertRedirect();
    }

    public function test_unauthenticated_user_cannot_access_evaluasi(): void
    {
        $response = $this->get(route('peserta.evaluasi.index'));

        $response->assertRedirect();
    }

    public function test_admin_can_view_evaluasi_responses(): void
    {
        $admin = User::factory()->admin()->create();

        $response = $this->actingAs($admin)
            ->get(route('filament.admin.resources.evaluasi-responses.index'));

        $response->assertStatus(200);
    }
}
```

#### 2.3.4 TahapanProgressionTest.php

**Path:** `tests/Feature/TahapanProgressionTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\PelatihanTahapan;
use App\Models\PelatihanTahapanProgress;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use App\Models\User;
use App\Services\TahapanProgressService;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class TahapanProgressionTest extends TestCase
{
    use RefreshDatabase;

    private Kegiatan $kegiatan;
    private Peserta $peserta;
    private RegistrasiUlang $registrasi;
    private TahapanProgressService $service;

    protected function setUp(): void
    {
        parent::setUp();

        $this->service = new TahapanProgressService();

        $user = User::factory()->peserta()->create();
        $this->peserta = Peserta::factory()->create([
            'user_id' => $user->id,
            'nik' => '3301010101010001',
        ]);

        $this->kegiatan = Kegiatan::factory()->create();

        $this->registrasi = RegistrasiUlang::factory()->bersedia()->create([
            'peserta_nik' => $this->peserta->nik,
            'kegiatan_id' => $this->kegiatan->id,
        ]);
    }

    public function test_first_tahapan_activated_when_registration_accepted(): void
    {
        $tahapan1 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 1,
        ]);

        $this->service->activateNextTahapan(
            (object) ['kegiatan_id' => $this->kegiatan->id, 'urutan' => 0],
            $this->peserta->nik
        );

        $this->assertDatabaseHas('pelatihan_tahapan_progress', [
            'tahapan_id' => $tahapan1->id,
            'peserta_nik' => $this->peserta->nik,
            'status' => 'active',
        ]);
    }

    public function test_completing_tahapan_activates_next(): void
    {
        $tahapan1 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 1,
        ]);
        $tahapan2 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 2,
        ]);

        $progress = $this->service->completeTahapan(
            $tahapan1->id,
            $this->peserta->nik,
            ['jawaban_1' => 'test']
        );

        $this->assertEquals('completed', $progress->status);
        $this->assertNotNull($progress->completed_at);

        $this->assertDatabaseHas('pelatihan_tahapan_progress', [
            'tahapan_id' => $tahapan2->id,
            'peserta_nik' => $this->peserta->nik,
            'status' => 'active',
        ]);
    }

    public function test_tahapan_locked_when_previous_not_completed(): void
    {
        $tahapan1 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 1,
        ]);
        $tahapan2 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 2,
        ]);

        // Coba complete tahapan 2 tanpa selesaikan tahapan 1
        $progress = $this->service->completeTahapan(
            $tahapan2->id,
            $this->peserta->nik
        );

        // Tahapan 2 completed, tapi tahapan 1 tidak
        $this->assertDatabaseHas('pelatihan_tahapan_progress', [
            'tahapan_id' => $tahapan1->id,
            'status' => 'active', // masih aktif, belum selesai
        ]);
    }

    public function test_progress_percentage_calculated_correctly(): void
    {
        $tahapans = collect();
        for ($i = 1; $i <= 4; $i++) {
            $tahapans->push(PelatihanTahapan::factory()->create([
                'kegiatan_id' => $this->kegiatan->id,
                'urutan' => $i,
            ]));
        }

        // Complete 2 of 4 tahapans
        $this->service->completeTahapan($tahapans[0]->id, $this->peserta->nik);
        $this->service->completeTahapan($tahapans[1]->id, $this->peserta->nik);

        $completed = PelatihanTahapanProgress::where('peserta_nik', $this->peserta->nik)
            ->where('status', 'completed')
            ->count();

        $percentage = ($completed / $tahapans->count()) * 100;

        $this->assertEquals(50, $percentage);
    }

    public function test_all_tahapan_complete_changes_status_to_selesai(): void
    {
        $tahapans = collect();
        for ($i = 1; $i <= 3; $i++) {
            $tahapans->push(PelatihanTahapan::factory()->create([
                'kegiatan_id' => $this->kegiatan->id,
                'urutan' => $i,
            ]));
        }

        foreach ($tahapans as $tahapan) {
            $this->service->completeTahapan($tahapan->id, $this->peserta->nik);
        }

        $this->registrasi->refresh();
        $this->assertEquals('selesai', $this->registrasi->status);
    }

    public function test_tahapan_sequence_respects_urutan(): void
    {
        $tahapan1 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 1,
        ]);
        $tahapan3 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 3,
        ]);
        $tahapan2 = PelatihanTahapan::factory()->create([
            'kegiatan_id' => $this->kegiatan->id,
            'urutan' => 2,
        ]);

        $this->service->activateNextTahapan(
            (object) ['kegiatan_id' => $this->kegiatan->id, 'urutan' => 0],
            $this->peserta->nik
        );

        // Next should be urutan 1, not 2 or 3
        $this->assertDatabaseHas('pelatihan_tahapan_progress', [
            'tahapan_id' => $tahapan1->id,
            'status' => 'active',
        ]);
    }
}
```

#### 2.3.5 NotificationTest.php

**Path:** `tests/Feature/NotificationTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use App\Models\User;
use App\Notifications\RegistrationStatusNotification;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\Notification;
use Tests\TestCase;

class NotificationTest extends TestCase
{
    use RefreshDatabase;

    public function test_notification_sent_on_status_change(): void
    {
        Notification::fake();

        $user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create([
            'user_id' => $user->id,
            'nik' => '3301010101010001',
        ]);
        $kegiatan = Kegiatan::factory()->create();
        $registrasi = RegistrasiUlang::factory()->pending()->create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatan->id,
        ]);

        $registrasi->update(['status' => 'diterima']);

        Notification::assertSentTo(
            $user,
            RegistrationStatusNotification::class
        );
    }

    public function test_database_notification_saved(): void
    {
        $user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create([
            'user_id' => $user->id,
            'nik' => '3301010101010001',
        ]);
        $kegiatan = Kegiatan::factory()->create();
        $registrasi = RegistrasiUlang::factory()->pending()->create([
            'peserta_nik' => $peserta->nik,
            'kegiatan_id' => $kegiatan->id,
        ]);

        $user->notify(new RegistrationStatusNotification($registrasi, 'diterima'));

        $this->assertDatabaseHas('notifications', [
            'notifiable_id' => $user->id,
            'type' => RegistrationStatusNotification::class,
        ]);
    }

    public function test_admin_receives_notification_on_new_registration(): void
    {
        Notification::fake();

        $admin = User::factory()->admin()->create();
        $user = User::factory()->peserta()->create();

        // Simulate admin notification
        $admin->notify(new RegistrationStatusNotification(
            (object) ['peserta_nama' => 'Test'],
            'new_registration'
        ));

        Notification::assertSentTo($admin, RegistrationStatusNotification::class);
    }
}
```

#### 2.3.6 FilamentResourceTest.php

**Path:** `tests/Feature/FilamentResourceTest.php`

```php
<?php

namespace Tests\Feature;

use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Livewire\Livewire;
use Tests\TestCase;

class FilamentResourceTest extends TestCase
{
    use RefreshDatabase;

    private User $admin;
    private User $peserta;

    protected function setUp(): void
    {
        parent::setUp();

        $this->admin = User::factory()->admin()->create();
        $this->peserta = User::factory()->peserta()->create();
    }

    public function test_admin_can_access_admin_panel(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin');

        $response->assertStatus(200);
    }

    public function test_guest_cannot_access_admin_panel(): void
    {
        $response = $this->get('/admin');

        $response->assertRedirect();
    }

    public function test_peserta_cannot_access_admin_panel(): void
    {
        $response = $this->actingAs($this->peserta)
            ->get('/admin');

        $response->assertStatus(403);
    }

    public function test_admin_can_view_peserta_resource(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/pesertas');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_kegiatan_resource(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/kegiatans');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_registrasi_resource(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/registrasi-ulangs');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_evaluasi_resource(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/evaluasis');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_pengaturan_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/pengaturans');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_laporan_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/laporan');

        $response->assertStatus(200);
    }

    public function test_admin_can_view_export_page(): void
    {
        $response = $this->actingAs($this->admin)
            ->get('/admin/export-data');

        $response->assertStatus(200);
    }
}
```

### 2.4 Unit Tests

#### 2.4.1 RegistrasiUlangTest.php

**Path:** `tests/Unit/RegistrasiUlangTest.php`

```php
<?php

namespace Tests\Unit;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class RegistrasiUlangTest extends TestCase
{
    use RefreshDatabase;

    public function test_uses_uuid_primary_key(): void
    {
        $registrasi = RegistrasiUlang::factory()->create();

        $this->assertIsString($registrasi->id);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $registrasi->id
        );
    }

    public function test_scope_pending(): void
    {
        RegistrasiUlang::factory()->pending()->create(['id' => 'reg-pending']);
        RegistrasiUlang::factory()->diterima()->create(['id' => 'reg-diterima']);

        $pending = RegistrasiUlang::pending()->get();

        $this->assertCount(1, $pending);
        $this->assertEquals('reg-pending', $pending->first()->id);
    }

    public function test_scope_diterima(): void
    {
        RegistrasiUlang::factory()->pending()->create(['id' => 'reg-pending']);
        RegistrasiUlang::factory()->diterima()->create(['id' => 'reg-diterima']);

        $diterima = RegistrasiUlang::diterima()->get();

        $this->assertCount(1, $diterima);
        $this->assertEquals('reg-diterima', $diterima->first()->id);
    }

    public function test_belongs_to_peserta(): void
    {
        $user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create(['user_id' => $user->id]);
        $registrasi = RegistrasiUlang::factory()->create([
            'peserta_nik' => $peserta->nik,
        ]);

        $this->assertNotNull($registrasi->peserta);
        $this->assertEquals($peserta->nik, $registrasi->peserta->nik);
    }

    public function test_belongs_to_kegiatan(): void
    {
        $kegiatan = Kegiatan::factory()->create();
        $registrasi = RegistrasiUlang::factory()->create([
            'kegiatan_id' => $kegiatan->id,
        ]);

        $this->assertNotNull($registrasi->kegiatan);
        $this->assertEquals($kegiatan->id, $registrasi->kegiatan->id);
    }
}
```

#### 2.4.2 PesertaTest.php

**Path:** `tests/Unit/PesertaTest.php`

```php
<?php

namespace Tests\Unit;

use App\Models\Kabupaten;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class PesertaTest extends TestCase
{
    use RefreshDatabase;

    public function test_uses_nik_as_primary_key(): void
    {
        $peserta = Peserta::factory()->create([
            'nik' => '3301010101010001',
        ]);

        $this->assertEquals('3301010101010001', $peserta->getKey());
        $this->assertEquals('3301010101010001', $peserta->nik);
    }

    public function test_belongs_to_kabupaten(): void
    {
        $kabupaten = Kabupaten::factory()->create();
        $peserta = Peserta::factory()->create([
            'kabupaten_id' => $kabupaten->id,
        ]);

        $this->assertNotNull($peserta->kabupaten);
        $this->assertEquals($kabupaten->id, $peserta->kabupaten->id);
    }

    public function test_belongs_to_user(): void
    {
        $user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create([
            'user_id' => $user->id,
        ]);

        $this->assertNotNull($peserta->user);
        $this->assertEquals($user->id, $peserta->user->id);
    }

    public function test_scope_by_kabupaten(): void
    {
        $kab1 = Kabupaten::factory()->create(['id' => 'kab-1']);
        $kab2 = Kabupaten::factory()->create(['id' => 'kab-2']);

        Peserta::factory()->create(['kabupaten_id' => 'kab-1']);
        Peserta::factory()->create(['kabupaten_id' => 'kab-1']);
        Peserta::factory()->create(['kabupaten_id' => 'kab-2']);

        $results = Peserta::where('kabupaten_id', 'kab-1')->get();

        $this->assertCount(2, $results);
    }
}
```

### 2.5 Refactor Existing Tests

#### 2.5.1 Move KegiatanTest.php to Feature

**Path:** `tests/Feature/KegiatanTest.php` (pindah dari `tests/Unit/`)

```php
<?php

namespace Tests\Feature;

use App\Models\Kegiatan;
use App\Models\Peserta;
use App\Models\RegistrasiUlang;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Support\Facades\DB;
use Tests\TestCase;

class KegiatanTest extends TestCase
{
    use RefreshDatabase;

    public function test_kegiatan_has_uuid_primary_key(): void
    {
        $kegiatan = Kegiatan::factory()->create();

        $this->assertIsString($kegiatan->id);
        $this->assertMatchesRegularExpression(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{4}-[0-9a-f]{12}$/',
            $kegiatan->id
        );
    }

    public function test_kegiatan_kuota_tersedia(): void
    {
        $kegiatan = Kegiatan::factory()->create(['kuota' => 30]);

        // Belum ada registrasi
        $this->assertEquals(30, $kegiatan->kuota_tersedia);

        // Tambah 5 registrasi diterima
        for ($i = 0; $i < 5; $i++) {
            $user = \App\Models\User::factory()->peserta()->create();
            $peserta = Peserta::factory()->create(['user_id' => $user->id]);
            RegistrasiUlang::factory()->diterima()->create([
                'peserta_nik' => $peserta->nik,
                'kegiatan_id' => $kegiatan->id,
            ]);
        }

        $this->assertEquals(25, $kegiatan->kuota_tersedia);
    }

    public function test_kegiatan_scope_aktif(): void
    {
        Kegiatan::factory()->create(['status' => 'active']);
        Kegiatan::factory()->create(['status' => 'inactive']);

        $aktif = Kegiatan::aktif()->get();

        $this->assertCount(1, $aktif);
        $this->assertEquals('active', $aktif->first()->status);
    }
}
```

**Hapus `tests/Unit/KegiatanTest.php`** karena sudah dipindah ke Feature.

#### 2.5.2 Refactor ApiTest.php

**Path:** `tests/Feature/ApiTest.php` (update untuk pakai factories)

```php
<?php

namespace Tests\Feature;

use App\Models\Kabupaten;
use App\Models\Kegiatan;
use App\Models\KegiatanType;
use App\Models\Peserta;
use App\Models\User;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;

class ApiTest extends TestCase
{
    use RefreshDatabase;

    public function test_cek_nik_returns_404_for_unknown_nik(): void
    {
        $response = $this->getJson('/api/cek-nik?nik=1234567890123456');

        $response->assertStatus(404)
            ->assertJson([
                'success' => false,
            ]);
    }

    public function test_cek_nik_returns_data_for_existing_peserta(): void
    {
        $user = User::factory()->peserta()->create();
        $peserta = Peserta::factory()->create([
            'user_id' => $user->id,
            'nik' => '3301010101010001',
            'nama' => 'Test Peserta',
        ]);

        $response = $this->getJson('/api/cek-nik?nik=3301010101010001');

        $response->assertOk()
            ->assertJson([
                'success' => true,
                'data' => [
                    'nik' => '3301010101010001',
                    'nama' => 'TEST PESERTA',
                ],
            ]);
    }

    public function test_kegiatan_list_returns_active_kegiatan(): void
    {
        KegiatanType::factory()->tanamanPangan()->create();
        Kegiatan::factory()->create([
            'status' => 'active',
        ]);

        $response = $this->getJson('/api/kegiatan');

        $response->assertOk()
            ->assertJson([
                'success' => true,
            ]);
    }

    public function test_daftar_pelatihan_requires_nik(): void
    {
        $response = $this->postJson('/api/daftar-pelatihan', []);

        $response->assertStatus(422);
    }
}
```

### 2.6 Run Tests

```bash
# Jalankan semua test
php artisan test

# Jalankan test tertentu
php artisan test tests/Feature/RegistrationFlowTest.php

# Jalankan dengan coverage report
php artisan test --coverage

# Jalankan test dengan verbose output
php artisan test --verbose
```

### 2.7 Test Summary

| File | Tests | Category |
|------|-------|----------|
| RegistrationFlowTest.php | 8 | Feature |
| CertificateTest.php | 6 | Feature |
| EvaluasiTest.php | 6 | Feature |
| TahapanProgressionTest.php | 7 | Feature |
| NotificationTest.php | 3 | Feature |
| FilamentResourceTest.php | 10 | Feature |
| RegistrasiUlangTest.php | 5 | Unit |
| PesertaTest.php | 4 | Unit |
| KegiatanTest.php (refactored) | 3 | Feature |
| ApiTest.php (refactored) | 4 | Feature |
| AuthTest.php (existing) | 4 | Feature |
| **Total** | **~60** | |

---

## 3. File Summary

### Deployment Files

| Action | File | Description |
|--------|------|-------------|
| Create | `DEPLOYMENT.md` | Dokumentasi ini |
| Edit | `.env.example` | Update SESSION_DRIVER=file, tambah production values |
| Edit | `.gitignore` | Tambah *.html, *.txt, *.csv, cookie*, cookies* |
| Edit | `composer.json` | Tambah post-install/post-update scripts |
| Delete | `body.html`, `dash.html`, `page.html`, `tahapan.html`, `video*.html` | Debug files |
| Delete | `cookie.txt`, `cookies.txt`, `headers.txt`, `login_headers.txt`, `video_headers.txt` | Debug files |
| Delete | `test-data-*.csv` | Test data files |

### Test Files

| Action | File | Description |
|--------|------|-------------|
| Create | `database/factories/KabupatenFactory.php` | Factory Kabupaten |
| Create | `database/factories/KegiatanTypeFactory.php` | Factory KegiatanType |
| Create | `database/factories/KegiatanFactory.php` | Factory Kegiatan |
| Create | `database/factories/PesertaFactory.php` | Factory Peserta |
| Create | `database/factories/RegistrasiUlangFactory.php` | Factory RegistrasiUlang |
| Create | `database/factories/EvaluasiFactory.php` | Factory Evaluasi |
| Create | `database/factories/EvaluasiTypeFactory.php` | Factory EvaluasiType |
| Edit | `database/factories/UserFactory.php` | Tambah role field + admin/peserta states |
| Create | `tests/Feature/RegistrationFlowTest.php` | 8 tests |
| Create | `tests/Feature/CertificateTest.php` | 6 tests |
| Create | `tests/Feature/EvaluasiTest.php` | 6 tests |
| Create | `tests/Feature/TahapanProgressionTest.php` | 7 tests |
| Create | `tests/Feature/NotificationTest.php` | 3 tests |
| Create | `tests/Feature/FilamentResourceTest.php` | 10 tests |
| Create | `tests/Unit/RegistrasiUlangTest.php` | 5 tests |
| Create | `tests/Unit/PesertaTest.php` | 4 tests |
| Move | `tests/Unit/KegiatanTest.php` → `tests/Feature/KegiatanTest.php` | Refactor |
| Edit | `tests/Feature/ApiTest.php` | Refactor pakai factories |
| Edit | `app/Models/User.php` | Pastikan ada HasFactory trait |

---

**Catatan:**
- Semua test menggunakan SQLite in-memory via phpunit.xml
- `RefreshDatabase` trait memastikan database bersih per test
- Factories memudahkan pembuatan data test yang konsisten
- Target coverage > 60% sesuai PRD Non-Functional Requirements
