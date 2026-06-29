# Product Requirements Document (PRD)

## Sistem Informasi Manajemen Pelatihan
### Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah

---

| Field | Value |
|-------|-------|
| **Nama Produk** | Sistem Informasi Manajemen Pelatihan Bapeltan Jateng |
| **Versi Dokumen** | 1.1 |
| **Tanggal** | 29 Juni 2026 |
| **Status** | In Progress (Phase 1 Selesai) |
| **Author** | Tim Pengembang |

---

## Daftar Isi

1. [Ringkasan Eksekutif](#1-ringkasan-eksekutif)
2. [Tujuan & Sasaran](#2-tujuan--sasaran)
3. [Pengguna & Role](#3-pengguna--role)
4. [Arsitektur Sistem](#4-arsitektur-sistem)
5. [Database Schema](#5-database-schema)
6. [Modul & Spesifikasi Fitur](#6-modul--spesifikasi-fitur)
7. [API Endpoints](#7-api-endpoints)
8. [UI/UX Requirements](#8-uiux-requirements)
9. [Keamanan](#9-keamanan)
10. [Roadmap Pengembangan](#10-roadmap-pengembangan)
11. [Issue & Tech Debt](#11-issue--tech-debt)
12. [Non-Functional Requirements](#12-non-functional-requirements)
13. [Glossary](#13-glossary)

---

## 1. Ringkasan Eksekutif

### 1.1 Latar Belakang

Balai Pelatihan Pertanian (Bapeltan) Jawa Tengah adalah institusi yang bertanggung jawab atas pelatihan dan pemberdayaan petani di seluruh wilayah Jawa Tengah. Proses pendaftaran, pengelolaan data peserta, dan evaluasi pelatihan selama ini dilakukan secara manual atau semi-digital, yang menyebabkan:

- Pendataan peserta yang belum terpusat
- Proses registrasi pelatihan yang lama dan rentan kesalahan
- Sulitnya memantau kuota dan jadwal pelatihan secara real-time
- Evaluasi pelatihan yang belum terstandarisasi
- Laporan yang sulit diakses dan dianalisis

### 1.2 Solusi

Dibangunnya Sistem Informasi Manajemen Pelatihan berbasis web yang terintegrasi untuk mengelola seluruh siklus pelatihan pertanian, mulai dari pendaftaran peserta, pengelolaan pelatihan, hingga evaluasi dan pelaporan.

### 1.3 Cakupan

Sistem ini mencakup:

- Panel administrasi untuk operator Bapeltan
- Panel peserta untuk petani/poktan
- Form registrasi publik tanpa login
- Manajemen data master (peserta, kabupaten, kegiatan)
- Sistem evaluasi multi-tipe jawaban
- Dashboard dan pelaporan

---

## 2. Tujuan & Sasaran

### 2.1 Tujuan

| # | Tujuan |
|---|--------|
| T1 | Mendigitalisasi seluruh proses pendaftaran dan pengelolaan pelatihan pertanian |
| T2 | Menyediakan database terpusat seluruh data peserta pelatihan di Jawa Tengah |
| T3 | Mempermudah monitoring kuota, jadwal, dan status pelatihan secara real-time |
| T4 | Menghasilkan evaluasi pelatihan yang terstandarisasi dan terukur |
| T5 | Menyediakan laporan yang akurat dan mudah diakses |

### 2.2 Sasaran

| # | Sasaran | KPI |
|---|---------|-----|
| S1 | Semua peserta pelatihan terdata dalam sistem | 100% peserta terdaftar |
| S2 | Proses registrasi pelatihan bisa dilakukan online | Waktu registrasi < 5 menit |
| S3 | Dashboard admin menampilkan data real-time | Update data < 1 detik |
| S4 | Evaluasi pelatihan bisa dilakukan secara digital | 100% evaluasi digital |
| S5 | Laporan bisa di-export dalam format Excel/PDF | Tersedia minimal 5 jenis laporan |

---

## 3. Pengguna & Role

### 3.1 Daftar Role

| Role | Deskripsi | Jumlah Estimasi |
|------|-----------|-----------------|
| **Admin** | Operator Bapeltan yang mengelola seluruh data dan konfigurasi sistem | 2-5 orang |
| **Peserta** | Petani atau anggota kelompok tani yang mendaftar dan mengikuti pelatihan | 500-2000 orang |
| **Publik** | Pengunjung website yang belum terdaftar (bisa mengakses form registrasi) | Tidak terbatas |

### 3.2 Hak Akses

#### Admin
| Aksi | Keterangan |
|------|------------|
| Kelola data peserta | CRUD biodata peserta |
| Kelola data kabupaten | CRUD data kabupaten |
| Kelola kegiatan pelatihan | CRUD pelatihan, assign kuota, set status |
| Kelola kegiatan type | CRUD jenis pelatihan |
| Kelola group WhatsApp | CRUD grup per pelatihan |
| Kelola registrasi ulang | Terima/tolak pendaftaran, upload sertifikat |
| Kelola evaluasi | Buat evaluasi, pertanyaan, opsi jawaban |
| Kelola pengaturan | Set judul, persyaratan, fasilitas, tanggal tutup |
| Lihat dashboard | Statistik jumlah peserta, pelatihan, registrasi |
| Export data | Download data peserta/registrasi ke Excel |

#### Peserta
| Aksi | Keterangan |
|------|------------|
| Lihat dashboard | Ringkasan pelatihan diikuti, status |
| Daftar pelatihan | Pilih kegiatan yang tersedia |
| Lihat status registrasi | Status pending/diterima/ditolak |
| Ikuti evaluasi | Jawab pertanyaan evaluasi (pre-test/post-test) |
| Lihat grup WhatsApp | Akses link grup pelatihan |
| Lihat sertifikat | Download sertifikat pelatihan |

#### Publik
| Aksi | Keterangan |
|------|------------|
| Lihat homepage | Informasi pelatihan, persyaratan, fasilitas |
| Registrasi biodata | Isi form pendaftaran berbasis NIK |
| Cek status NIK | Verifikasi apakah NIK sudah terdaftar |
| Daftar pelatihan | Pilih kegiatan (setelah biodata terdaftar) |

---

## 4. Arsitektur Sistem

### 4.1 Tech Stack

| Layer | Teknologi | Versi |
|-------|-----------|-------|
| **Backend Framework** | Laravel | 12.x |
| **Admin Panel** | Filament | 3.3 |
| **Livewire** | Livewire | (bawaan Laravel 12) |
| **CSS Framework** | Tailwind CSS | 4.x |
| **UI Component** | DaisyUI | 5.x |
| **JavaScript** | Alpine.js | (bawaan Livewire) |
| **Build Tool** | Vite | 7.x |
| **Database (Dev)** | SQLite | - |
| **Database (Prod)** | MySQL / MariaDB | 8.x / 10.x |
| **PHP** | PHP | 8.2+ |

### 4.2 Arsitektur Aplikasi

```
┌─────────────────────────────────────────────────────┐
│                    CLIENT SIDE                       │
│                                                      │
│  ┌──────────┐  ┌──────────┐  ┌──────────────────┐  │
│  │ Homepage  │  │ Register │  │  Login Page      │  │
│  │ (Public)  │  │  Form    │  │  (Public)        │  │
│  └──────────┘  └──────────┘  └──────────────────┘  │
└─────────────────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────┐
│                    SERVER SIDE                       │
│                    Laravel 12                        │
│                                                      │
│  ┌─────────────────────────────────────────────┐    │
│  │              Routes                          │    │
│  │  web.php  │  api.php  │  Filament Routes    │    │
│  └─────────────────────────────────────────────┘    │
│                         │                            │
│  ┌──────────────────────┼─────────────────────┐    │
│  │         Middleware                         │    │
│  │   auth  │  Admin  │  Peserta  │  throttle  │    │
│  └──────────────────────┼─────────────────────┘    │
│                         │                            │
│  ┌──────────────────────┼─────────────────────┐    │
│  │         Controllers / Livewire             │    │
│  │  SignInController │ PublicRegistrationForm  │    │
│  │                   │ PublicTrainingRegistration│   │
│  └──────────────────────┼─────────────────────┘    │
│                         │                            │
│  ┌──────────────────────┼─────────────────────┐    │
│  │         Models (Eloquent ORM)              │    │
│  │  User │ Peserta │ Kegiatan │ RegistrasiUlang│    │
│  │  Evaluasi* │ Group │ Kabupaten │ Pengaturan  │    │
│  └──────────────────────┼─────────────────────┘    │
│                         │                            │
│  ┌──────────────────────┼─────────────────────┐    │
│  │         Filament Panels                    │    │
│  │  Admin Panel (/admin) │ Peserta Panel (/peserta)│  │
│  └──────────────────────┼─────────────────────┘    │
└─────────────────────────────────────────────────────┘
                         │
                         ▼
┌─────────────────────────────────────────────────────┐
│                   DATABASE                          │
│           SQLite (dev) / MySQL (prod)                │
│                                                      │
│  users │ pesertas │ kegiatans │ registrasi_ulangs   │
│  evaluasi* │ groups │ kabupatens │ pengaturans      │
└─────────────────────────────────────────────────────┘
```

### 4.3 Struktur Folder

```
bapeltan/
├── app/
│   ├── Filament/
│   │   ├── Pages/
│   │   │   └── Pengaturans.php              # Halaman pengaturan
│   │   ├── Peserta/                          # Panel Peserta
│   │   │   ├── Pages/
│   │   │   │   ├── PesertaDashboard.php
│   │   │   │   └── TakeEvaluasi.php
│   │   │   └── Resources/
│   │   │       ├── EvaluasiResource.php
│   │   │       ├── GroupResource.php
│   │   │       ├── RegistrasiUlangResource.php
│   │   │       └── RegistrasiZilenialResource.php
│   │   └── Resources/                        # Panel Admin
│   │       ├── EvaluasiResource.php
│   │       ├── EvaluasiTypeResource.php
│   │       ├── GroupResource.php
│   │       ├── KabupatenResource.php
│   │       ├── KegiatanResource.php
│   │       ├── PesertaResource.php
│   │       ├── RegistrasiUlangResource.php
│   │       └── RegistrasiZilenialResource.php
│   ├── Http/
│   │   ├── Controllers/
│   │   │   ├── Controller.php
│   │   │   └── SignInController.php
│   │   └── Middleware/
│   │       ├── Admin.php
│   │       └── Peserta.php
│   ├── Livewire/
│   │   ├── PublicRegistrationForm.php
│   │   └── PublicTrainingRegistration.php
│   ├── Models/
│   │   ├── Evaluasi.php
│   │   ├── EvaluasiQuestion.php
│   │   ├── EvaluasiQuestionOption.php
│   │   ├── EvaluasiResponse.php
│   │   ├── EvaluasiType.php
│   │   ├── Group.php
│   │   ├── Kabupaten.php
│   │   ├── Kegiatan.php
│   │   ├── KegiatanType.php
│   │   ├── Pengaturan.php
│   │   ├── Peserta.php
│   │   ├── RegistrasiUlang.php
│   │   ├── RegistrasiZilenial.php
│   │   └── User.php
│   └── Providers/
│       ├── AppServiceProvider.php
│       └── Filament/
│           ├── AdminPanelProvider.php
│           └── PesertaPanelProvider.php
├── database/
│   ├── factories/
│   ├── migrations/                           # 16 migration files
│   └── seeders/
│       ├── DatabaseSeeder.php
│       ├── KabupatenSeeder.php
│       ├── KegiatanSeeder.php
│       ├── KegiatanTypeSeeder.php
│       └── UserSeeder.php
├── resources/
│   ├── css/
│   │   └── app.css
│   ├── js/
│   │   ├── app.js
│   │   └── bootstrap.js
│   └── views/
│       ├── auth/
│       │   └── sign-in.blade.php
│       ├── components/                       # 11 component views
│       ├── filament/                         # Filament blade overrides
│       └── livewire/                         # Livewire views
├── routes/
│   ├── web.php
│   └── console.php
└── config/
    ├── app.php
    ├── auth.php
    ├── database.php
    └── ...
```

---

## 5. Database Schema

### 5.1 Entity Relationship Diagram

```
┌──────────────┐       ┌──────────────────┐       ┌──────────────────┐
│    users      │       │    pesertas       │       │   kabupatens     │
├──────────────┤       ├──────────────────┤       ├──────────────────┤
│ id (PK)      │◄──┐   │ nik (PK, VARCHAR)│       │ id (PK)          │
│ name         │   └───│ user_id (FK, n)  │       │ code (UNIQUE)    │
│ email (UNIQUE)│       │ nama             │       │ name             │
│ role (ENUM)  │       │ tempat_lahir     │       └────────┬─────────┘
│ password     │       │ tanggal_lahir    │                │
│ remember_token│      │ nomor_telepon    │                │ 1:N
│ timestamps   │       │ agama (ENUM)     │                │
└──────────────┘       │ jenis_kelamin    │       ┌────────▼─────────┐
                       │ status_pernikahan│       │   pesertas       │
                       │ pendidikan_terakhir     │  (kabupaten_id)  │
                       │ pekerjaan        │       └────────┬─────────┘
                       │ usaha_tani       │                │
                       │ alamat_lengkap   │                │ 1:N
                       │ nama_poktan      │                │
                       │ alamat_poktan    │       ┌────────▼─────────┐
                       │ nip (nullable)   │       │ registrasi_ulangs│
                       │ email            │       ├──────────────────┤
                       │ kabupaten_id (FK)│       │ id (PK)          │
                       │ timestamps       │       │ peserta_nik (FK) │
                       └────────┬─────────┘       │ kegiatan_id (FK) │
                                │                 │ status (ENUM)    │
                                │ 1:N             │ catatan           │
                                │                 │ timestamps        │
                       ┌────────▼─────────┐       └────────┬─────────┘
                       │ registrasi_ulangs│                │
                       └──────────────────┘                │ N:1
                                                          │
┌──────────────┐       ┌──────────────────┐       ┌───────▼──────────┐
│kegiatan_types│       │    kegiatans      │       │    kegiatans     │
├──────────────┤       ├──────────────────┤       ├──────────────────┤
│ id (PK)      │◄──┐   │ id (PK)          │       │ id (PK)          │
│ nama_type    │   └───│ kegiatan_type_id │       │ nama_pelatihan   │
│ timestamps   │       │ nama_pelatihan   │       │ kode_pelatihan   │
└──────────────┘       │ kode_pelatihan   │       │ tanggal_mulai    │
                       │ tanggal_mulai    │       │ tanggal_selesai  │
                       │ tanggal_selesai  │       │ kuota            │
                       │ kuota            │       │ status (ENUM)    │
                       │ status (ENUM)    │       │ deskripsi         │
                       │ deskripsi         │       │ group_id (FK, n) │
                       │ group_id (FK, n) │       │ timestamps        │
                       │ timestamps       │       └──────────────────┘
                       └────────┬─────────┘
                                │
                                │ 1:N
                       ┌────────▼─────────┐
                       │     groups        │
                       ├──────────────────┤
                       │ id (PK)          │
                       │ name             │
                       │ group_link (UNIQUE)│
                       │ group_username   │
                       │ description       │
                       │ status (ENUM)    │
                       │ kegiatan_id (FK) │
                       │ timestamps        │
                       └──────────────────┘

┌──────────────────┐   ┌──────────────────┐   ┌──────────────────┐
│  evaluasi_types   │   │    evaluasis      │   │evaluasi_questions │
├──────────────────┤   ├──────────────────┤   ├──────────────────┤
│ id (PK)          │◄──│ evaluasi_type_id │   │ id (PK)          │
│ nama             │   │ kegiatan_id (FK) │◄──│ evaluasi_id (FK) │
│ deskripsi         │   │ judul            │   │ pertanyaan       │
│ timestamps        │   │ timestamps       │   │ tipe_jawaban     │
└──────────────────┘   └──────────────────┘   │ urutan           │
                                              │ timestamps       │
┌──────────────────────┐                      └────────┬─────────┘
│evaluasi_question_options                             │
├──────────────────────┤                      ┌────────▼─────────┐
│ id (PK)              │                      │evaluasi_responses │
│ evaluasi_question_id │                      ├──────────────────┤
│ value                │                      │ id (PK)          │
│ is_correct (BOOLEAN) │                      │ evaluasi_id (FK) │
│ timestamps           │                      │ question_id (FK) │
└──────────────────────┘                      │ registrasi_ulang_id│
                                              │ jawaban           │
                                              │ timestamps        │
                                              └──────────────────┘

┌──────────────────┐   ┌──────────────────────┐
│   pengaturans     │   │registrasi_zilenials  │
├──────────────────┤   ├──────────────────────┤
│ id (PK)          │   │ id (PK)              │
│ judul            │   │ timestamps            │
│ sub_judul        │   └──────────────────────┘
│ tanggal_tutup    │      (belum diimplementasi)
│ info              │
│ lokasi           │
│ persyaratan (JSON)│
│ fasilitas (JSON) │
│ timestamps        │
└──────────────────┘
```

### 5.2 Detail Tabel

#### 5.2.1 `users`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID user |
| name | varchar(255) | required | Nama lengkap |
| email | varchar(255) | unique, required | Email untuk login |
| role | enum('admin','peserta') | required | Role pengguna |
| email_verified_at | timestamp | nullable | Waktu verifikasi email |
| password | varchar(255) | required | Password (hashed) |
| remember_token | varchar(100) | nullable | Token "ingat saya" |
| created_at | timestamp | auto | Waktu pembuatan |
| updated_at | timestamp | auto | Waktu update terakhir |

#### 5.2.2 `pesertas`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| nik | varchar(16) | PK | Nomor Induk Kependudukan |
| user_id | bigint | FK → users.id, nullable, nullOnDelete | ID user terkait |
| nama | varchar(255) | required | Nama lengkap |
| tempat_lahir | varchar(255) | required | Tempat lahir |
| tanggal_lahir | date | required | Tanggal lahir |
| nomor_telepon | varchar(20) | required | No. HP/WA |
| agama | enum | required | Agama |
| jenis_kelamin | enum('L','P') | required | Laki-laki / Perempuan |
| status_pernikahan | enum | required | Status perkawinan |
| pendidikan_terakhir | enum | required | Pendidikan terakhir |
| pekerjaan | varchar(255) | required | Pekerjaan utama |
| usaha_tani | varchar(255) | required | Jenis usaha tani |
| alamat_lengkap | text | required | Alamat lengkap |
| nama_poktan | varchar(255) | required | Nama kelompok tani |
| alamat_poktan | varchar(255) | required | Alamat kelompok tani |
| nip | varchar(18) | nullable | NIP (jika ada) |
| email | varchar(255) | nullable | Email peserta |
| kabupaten_id | bigint | FK → kabupatens.id | ID kabupaten |
| created_at | timestamp | auto | Waktu pendaftaran |
| updated_at | timestamp | auto | Waktu update terakhir |

#### 5.2.3 `kabupatens`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID kabupaten |
| code | varchar(10) | unique | Kode wilayah |
| name | varchar(255) | required | Nama kabupaten |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.4 `kegiatan_types`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID jenis kegiatan |
| nama_type | varchar(255) | required | Nama jenis pelatihan |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.5 `kegiatans`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID kegiatan |
| nama_pelatihan | varchar(255) | required | Nama pelatihan |
| kode_pelatihan | varchar(50) | unique, required | Kode unik pelatihan |
| tanggal_mulai | date | required | Tanggal mulai |
| tanggal_selesai | date | required | Tanggal selesai |
| kuota | integer | required | Jumlah kuota peserta |
| status | enum('active','inactive') | default: 'active' | Status kegiatan |
| deskripsi | text | nullable | Deskripsi pelatihan |
| kegiatan_type_id | bigint | FK → kegiatan_types.id | Jenis kegiatan |
| group_id | bigint | FK → groups.id, nullable | Grup terkait |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.6 `groups`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID group |
| name | varchar(255) | required | Nama grup |
| group_link | varchar(255) | unique | Link undangan grup WA |
| group_username | varchar(255) | nullable | Username grup |
| description | text | nullable | Deskripsi grup |
| status | enum('active','inactive') | default: 'active' | Status grup |
| kegiatan_id | bigint | FK → kegiatans.id | Kegiatan terkait |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.7 `registrasi_ulangs`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID registrasi |
| peserta_nik | varchar(16) | FK → pesertas.nik | NIK peserta |
| kegiatan_id | bigint | FK → kegiatans.id | Kegiatan yang dipilih |
| status | enum('pending','diterima','ditolak','selesai') | default: 'pending' | Status registrasi |
| catatan | text | nullable | Catatan admin |
| created_at | timestamp | auto | Waktu pendaftaran |
| updated_at | timestamp | auto | Waktu update terakhir |

**Unique Constraint**: `(peserta_nik, kegiatan_type_id, tahun)` — satu peserta hanya bisa mendaftar satu jenis kegiatan per tahun.

#### 5.2.8 `registrasi_zilenials`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

> **Status**: Belum diimplementasi. Tabel kosong.

#### 5.2.9 `evaluasi_types`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID tipe evaluasi |
| nama | varchar(255) | required | Nama tipe (Pre-Test, Post-Test, dll) |
| deskripsi | text | nullable | Deskripsi tipe |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.10 `evaluasis`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID evaluasi |
| kegiatan_id | bigint | FK → kegiatans.id | Kegiatan terkait |
| evaluasi_type_id | bigint | FK → evaluasi_types.id | Tipe evaluasi |
| judul | varchar(255) | required | Judul evaluasi |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.11 `evaluasi_questions`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID pertanyaan |
| evaluasi_id | bigint | FK → evaluasis.id | Evaluasi terkait |
| pertanyaan | text | required | Teks pertanyaan |
| tipe_jawaban | varchar(50) | default: 'text' | Tipe: radio, checkbox, scale, text |
| urutan | integer | nullable | Urutan pertanyaan |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.12 `evaluasi_question_options`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID opsi |
| evaluasi_question_id | bigint | FK → evaluasi_questions.id | Pertanyaan terkait |
| value | varchar(255) | required | Teks opsi jawaban |
| is_correct | boolean | default: false | Apakah ini jawaban benar |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.13 `evaluasi_responses`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID jawaban |
| evaluasi_id | bigint | FK → evaluasis.id | Evaluasi terkait |
| question_id | bigint | FK → evaluasi_questions.id | Pertanyaan terkait |
| registrasi_ulang_id | bigint | FK → registrasi_ulangs.id | Registrasi peserta |
| jawaban | text | nullable | Jawaban peserta |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

#### 5.2.14 `pengaturans`

| Kolom | Tipe | Constraint | Keterangan |
|-------|------|------------|------------|
| id | bigint | PK, auto-increment | ID pengaturan |
| judul | varchar(255) | required | Judul utama |
| sub_judul | varchar(255) | required | Sub judul |
| tanggal_tutup | date | required | Tanggal tutup pendaftaran |
| info | text | nullable | Informasi umum |
| lokasi | varchar(255) | nullable | Lokasi pelatihan |
| persyaratan | json | nullable | Array persyaratan |
| fasilitas | json | nullable | Array fasilitas |
| created_at | timestamp | auto | - |
| updated_at | timestamp | auto | - |

---

## 6. Modul & Spesifikasi Fitur

### 6.1 Modul Autentikasi

#### 6.1.1 Login

| Field | Detail |
|-------|--------|
| **Endpoint** | `POST /login` |
| **Middleware** | Guest |
| **Input** | email, password, remember_me (boolean) |
| **Logic** | 1. Validasi input<br>2. Cari user by email<br>3. Verifikasi password (Hash::check)<br>4. Buat session<br>5. Redirect berdasarkan role |
| **Redirect** | admin → `/admin`, peserta → `/peserta` |
| **Error** | "Email atau password salah" |
| **Rate Limit** | 5 percobaan per 1 menit |

#### 6.1.2 Logout

| Field | Detail |
|-------|--------|
| **Endpoint** | `POST /logout` |
| **Middleware** | Auth |
| **Logic** | Hapus session, invalidate, regenerasi token |
| **Redirect** | `/` (halaman login) |

#### 6.1.3 Registrasi Publik (Biodata)

| Field | Detail |
|-------|--------|
| **Endpoint** | `GET /register` |
| **Component** | Livewire: `PublicRegistrationForm` |
| **Middleware** | Guest |
| **Input Form** | nik (16 digit, PK), nama, tempat_lahir, tanggal_lahir, nomor_telepon, agama, jenis_kelamin, status_pernikahan, pendidikan_terakhir, pekerjaan, usaha_tani, alamat_lengkap, nama_poktan, alamat_poktan, nip (optional), email (optional), kabupaten_id, password, password_confirmation |
| **Logic** | 1. Validasi semua input<br>2. Cek apakah NIK sudah ada di tabel `pesertas`<br>3. Buat record di `users` (role: peserta)<br>4. Buat record di `pesertas` (user_id terkait)<br>5. Redirect ke halaman sukses atau login |
| **Validasi NIK** | Harus 16 digit, unique di tabel pesertas |

#### 6.1.4 API Cek NIK

| Field | Detail |
|-------|--------|
| **Endpoint** | `GET /api/cek-nik?nik={nik}` |
| **Middleware** | Web (CSRF) |
| **Input** | nik (query parameter, 16 digit) |
| **Logic** | 1. Validasi NIK (16 digit)<br>2. Cari peserta by NIK<br>3. Jika tidak ada → error<br>4. Load relasi `registrasiUlangs.kegiatan`<br>5. Return data peserta + daftar kegiatan |
| **Response Success** | `{ success: true, data: { nik, nama, alamat, poktan, kegiatan[] } }` |
| **Response Error** | `{ success: false, message: "..." }` |
| **Rate Limit** | 30 request per menit |

#### 6.1.5 API Daftar Pelatihan

| Field | Detail |
|-------|--------|
| **Endpoint** | `POST /api/daftar-pelatihan` |
| **Middleware** | Web (CSRF) |
| **Input** | nik (16 digit), kegiatan_id (exists: kegiatans.id) |
| **Logic** | 1. Validasi input<br>2. Cari peserta by NIK<br>3. Cari kegiatan by ID<br>4. Cek duplikasi registrasi<br>5. Cek konflik jadwal (tidak ada pelatihan lain di tanggal yang sama)<br>6. Cek kuota tersedia<br>7. Buat registrasi (status: pending)<br>8. Return success |
| **Validasi Jadwal** | Tidak boleh overlap dengan pelatihan lain yang sudah didaftar |
| **Validasi Kuota** | Jumlah `diterima` < kuota kegiatan |
| **Response Success** | `{ success: true, message: "Pendaftaran berhasil!" }` |
| **Response Error** | `{ success: false, message: "..." }` |

---

### 6.2 Modul Admin Panel

#### 6.2.1 Dashboard Admin

| Field | Detail |
|-------|--------|
| **Path** | `/admin` |
| **Widget** | - Total peserta terdaftar<br>- Jumlah pelatihan aktif<br>- Registrasi pending<br>- Registrasi diterima bulan ini |
| **Navigation** | Sidebar dengan nav groups: DATA, EVALUASI |

#### 6.2.2 Manajemen Peserta

| Field | Detail |
|-------|--------|
| **Resource** | `PesertaResource` |
| **Path** | `/admin/pesertas` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, search, filter, create, edit, delete |
| **Form Fields** | nik, nama, tempat_lahir, tanggal_lahir, nomor_telepon, agama, jenis_kelamin, status_pernikahan, pendidikan_terakhir, pekerjaan, usaha_tani, alamat_lengkap, nama_poktan, alamat_poktan, nip, email, kabupaten_id |
| **Action Create** | Otomatis buat User (role: peserta) dengan password default |
| **Table Columns** | nik, nama, nama_poktan, kabupaten, nomor_telepon |
| **Relations** | belongsTo(Kabupaten), hasMany(RegistrasiUlang) |

#### 6.2.3 Manajemen Kabupaten

| Field | Detail |
|-------|--------|
| **Resource** | `KabupatenResource` |
| **Path** | `/admin/kabupatens` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, search, create, edit, delete |
| **Form Fields** | code, name |
| **Table Columns** | code, name |
| **Seed Data** | 30 kabupaten Jawa Tengah |

#### 6.2.4 Manajemen Kegiatan

| Field | Detail |
|-------|--------|
| **Resource** | `KegiatanResource` |
| **Path** | `/admin/kegiatans` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, search, filter by status/type, create, edit, delete |
| **Form Fields** | nama_pelatihan, kode_pelatihan (unique), tanggal_mulai, tanggal_selesai, kuota, status, deskripsi, kegiatan_type_id, group_id |
| **Table Columns** | kode_pelatihan, nama_pelatihan, kegiatan_type, tanggal_mulai, tanggal_selesai, kuota, status, jumlah_terdaftar |
| **Computed** | `jumlah_peserta_diterima` (accessor), `kuota_tersedia` (accessor) |
| **Relations** | belongsTo(KegiatanType), hasMany(RegistrasiUlang), belongsToMany(Peserta) |

#### 6.2.5 Manajemen Kegiatan Type

| Field | Detail |
|-------|--------|
| **Resource** | `KegiatanTypeResource` |
| **Path** | `/admin/kegiatan-types` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, create, edit, delete |
| **Form Fields** | nama_type |
| **Seed Data** | Agribisnis Tanaman Pangan, Agribisnis Perkebunan, Mekanisasi Pertanian |

#### 6.2.6 Manajemen Group

| Field | Detail |
|-------|--------|
| **Resource** | `GroupResource` |
| **Path** | `/admin/groups` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, create, edit, delete |
| **Form Fields** | name, group_link (unique), group_username, description, status, kegiatan_id |
| **Table Columns** | name, group_link, status, kegiatan |

#### 6.2.7 Manajemen Registrasi Ulang

| Field | Detail |
|-------|--------|
| **Resource** | `RegistrasiUlangResource` |
| **Path** | `/admin/registrasi-ulangs` |
| **Nav Group** | DATA |
| **Fitur** | Tabel list, filter by status, bulk actions, individual actions |
| **Form Fields** | peserta_nik, kegiatan_id, status, catatan |
| **Table Columns** | peserta.nama, kegiatan.nama_pelatihan, status, catatan, created_at |
| **Actions** | - **Terima**: Set status = diterima<br>- **Tolak**: Set status = ditolak, input catatan<br>- **Selesai**: Set status = selesai<br>- **Upload Sertifikat**: Upload file sertifikat |
| **Status Workflow** | pending → diterima / ditolak → selesai |
| **Relations** | belongsTo(Peserta), belongsTo(Kegiatan) |

#### 6.2.8 Manajemen Evaluasi Type

| Field | Detail |
|-------|--------|
| **Resource** | `EvaluasiTypeResource` |
| **Path** | `/admin/evaluasi-types` |
| **Nav Group** | EVALUASI |
| **Fitur** | Tabel list, create, edit, delete |
| **Form Fields** | nama, deskripsi |

#### 6.2.9 Manajemen Evaluasi

| Field | Detail |
|-------|--------|
| **Resource** | `EvaluasiResource` |
| **Path** | `/admin/evaluasis` |
| **Nav Group** | EVALUASI |
| **Fitur** | Tabel list, create, edit, delete + RelationManager untuk Questions |
| **Form Fields** | kegiatan_id, evaluasi_type_id, judul |
| **Relation Manager** | `QuestionsRelationManager` — kelola pertanyaan & opsi jawaban |
| **Tipe Jawaban** | - **radio**: Pilihan ganda (single answer)<br>- **checkbox**: Pilihan ganda (multiple answers)<br>- **scale**: Skala 1-5<br>- **text**: Jawaban bebas |

#### 6.2.10 Pengaturan

| Field | Detail |
|-------|--------|
| **Page** | `Pengaturans` |
| **Path** | `/admin/pengaturans` |
| **Fitur** | Form settings dengan tabs |
| **Tabs** | - **UMUM**: judul, sub_judul, tanggal_tutup, info, lokasi<br>- **PERSYARATAN**: Repeater (tambah/hapus item)<br>- **FASILITAS**: Repeater (tambah/hapus item) |
| **Form Fields** | judul, sub_judul, tanggal_tutup, info, lokasi, persyaratan[] (repeater), fasilitas[] (repeater) |

---

### 6.3 Modul Peserta Panel

#### 6.3.1 Dashboard Peserta

| Field | Detail |
|-------|--------|
| **Path** | `/peserta` |
| **Component** | `PesertaDashboard` |
| **Content** | Halaman sapaan dengan informasi pelatihan |

#### 6.3.2 Registrasi Ulang (Peserta)

| Field | Detail |
|-------|--------|
| **Resource** | `RegistrasiUlangResource` |
| **Path** | `/peserta/registrasi-ulangs` |
| **Fitur** | List kegiatan yang tersedia, aksi "Daftar" |
| **Logic** | - Tampilkan hanya kegiatan dengan status `active`<br>- Filter kegiatan yang belum didaftar<br>- Action "Daftar" buat registrasi baru (status: pending) |
| **Validasi** | Cek duplikasi, cek jadwal konflik, cek kuota |

#### 6.3.3 Evaluasi (Peserta)

| Field | Detail |
|-------|--------|
| **Resource** | `EvaluasiResource` (Peserta panel) |
| **Path** | `/peserta/evaluasis` |
| **Filter** | Hanya tampilkan evaluasi untuk kegiatan yang registrasinya `diterima` |
| **Action** | "Ikuti Evaluasi" → form halaman `TakeEvaluasi` |

#### 6.3.4 Take Evaluasi

| Field | Detail |
|-------|--------|
| **Page** | `TakeEvaluasi` |
| **Path** | `/peserta/evaluasis/{evaluasi}/take` |
| **Component** | Livewire form |
| **Logic** | 1. Load evaluasi beserta questions & options<br>2. Tampilkan form berdasarkan tipe_jawaban setiap pertanyaan<br>3. Simpan jawaban ke `evaluasi_responses`<br>4. Tandai evaluasi sudah diikuti |
| **Tipe Form** | - **radio**: Radio button group<br> - **checkbox**: Checkbox group<br> - **scale**: Slider atau dropdown 1-5<br> - **text**: Textarea |

#### 6.3.5 Group (Peserta)

| Field | Detail |
|-------|--------|
| **Resource** | `GroupResource` (Peserta panel) |
| **Path** | `/peserta/groups` |
| **Logic** | Hanya tampilkan grup untuk kegiatan yang registrasinya `diterima` |
| **Content** | Nama grup, link grup WhatsApp, deskripsi |

---

### 6.4 Modul Frontend Publik

#### 6.4.1 Homepage

| Field | Detail |
|-------|--------|
| **Path** | `/` (redirect ke login) |
| **Component** | `sign-in.blade.php` |
| **Sections** | 1. Navbar (sticky, green)<br>2. Hero/Welcome section<br>3. Pelatihan section<br>4. Artikel section<br>5. Video section<br>6. Partner section<br>7. Login modal<br>8. Footer (top + bottom) |

#### 6.4.2 Registrasi Form

| Field | Detail |
|-------|--------|
| **Path** | `/register` |
| **Component** | Livewire: `PublicRegistrationForm` |
| **Layout** | `livewire/layouts/public.blade.php` |
| **Fitur** | - Form multi-step atau single-page<br> - Validasi real-time via Livewire<br> - Cek NIK via Alpine.js + AJAX<br> - Submit dan redirect |

---

## 7. API Endpoints

### 7.1 Public API — `routes/api.php` (Tanpa Autentikasi, Throttled)

| Method | Endpoint | Rate Limit | Input | Response |
|--------|----------|------------|-------|----------|
| GET | `/api/cek-nik?nik={nik}` | 30/min | nik: string(16, numeric) | `{ success, data: { nik, nama, alamat, poktan, kegiatan[] } }` atau `{ success: false, message }` |
| POST | `/api/daftar-pelatihan` | 10/min | nik: string(16), kegiatan_id: integer (exists) | `{ success, message }` — daftar dengan `kegiatan_type_id` & `tahun` otomatis |

### 7.2 Web Routes — `routes/web.php`

| Method | Endpoint | Auth | Middleware | Controller |
|--------|----------|------|-----------|------------|
| GET | `/` | No | Guest | `SignInController@showLoginForm` |
| POST | `/login` | No | Guest, throttle:5,1 | `SignInController@login` |
| POST | `/logout` | Yes | Auth | `SignInController@logout` |
| GET | `/register` | No | Guest | Livewire: `PublicRegistrationForm` |

### 7.3 Filament Panel Routes

| Panel | Path Prefix | Auth | Middleware |
|-------|-------------|------|-----------|
| Admin | `/admin` | Yes | auth, Admin |
| Peserta | `/peserta` | Yes | auth, Peserta |

---

## 8. UI/UX Requirements

### 8.1 Desain System

| Komponen | Detail |
|----------|--------|
| **Color Palette** | Green-based (hijau pertanian) |
| **Primary Color** | Green (Tailwind: green-600/700) |
| **Font** | Instrument Sans (Google Fonts) |
| **Framework CSS** | Tailwind CSS v4 |
| **Component Library** | DaisyUI 5.x |
| **Icons** | Heroicons (via Filament) |

### 8.2 Halaman Utama

#### Homepage (Login Page)
```
┌─────────────────────────────────────────────┐
│  NAVBAR (sticky)                            │
│  [Logo] Beranda | Pelatihan | Artikel |     │
│         Video | Partner | [Login] [Daftar]  │
├─────────────────────────────────────────────┤
│                                             │
│  HERO SECTION                               │
│  "Selamat Datang di Bapeltan Jateng"        │
│  Subtitle + CTA Button                      │
│                                             │
├─────────────────────────────────────────────┤
│  PELATIHAN SECTION                          │
│  [Card 1] [Card 2] [Card 3] [Card 4]       │
│  (Floating shadow cards)                    │
├─────────────────────────────────────────────┤
│  ARTIKEL SECTION                            │
│  [Card 1] [Card 2] [Card 3]                │
│  [Card 4] [Card 5] [Card 6]                │
├─────────────────────────────────────────────┤
│  VIDEO SECTION                              │
│  [Video 1] [Video 2] [Video 3]             │
│  [Video 4] [Video 5] [Video 6]             │
├─────────────────────────────────────────────┤
│  PARTNER SECTION                            │
│  [Logo 1] [Logo 2] [Logo 3]                │
├─────────────────────────────────────────────┤
│  FOOTER                                     │
│  Contact info | Navigation | Copyright      │
└─────────────────────────────────────────────┘
```

#### Login Modal
```
┌─────────────────────────────────┐
│        LOGIN FORM               │
│                                 │
│  Email: [_________________]     │
│  Password: [_________________]  │
│  [x] Ingat saya                 │
│                                 │
│  [      LOGIN      ]            │
│                                 │
│  Belum punya akun? Daftar       │
└─────────────────────────────────┘
```

#### Registrasi Form
```
┌─────────────────────────────────────┐
│  FORM REGISTRASI BIODATA            │
│                                     │
│  NIK: [_________________] [Cek]    │
│  (Jika NIK sudah ada, tampilkan     │
│   data peserta + daftar pelatihan)  │
│                                     │
│  Nama Lengkap: [_________________]  │
│  Tempat Lahir: [_________________]  │
│  Tanggal Lahir: [__/__/____]        │
│  No. HP: [_________________]        │
│  Agama: [Dropdown]                  │
│  Jenis Kelamin: [L] [P]            │
│  Status Pernikahan: [Dropdown]      │
│  Pendidikan: [Dropdown]             │
│  Pekerjaan: [_________________]     │
│  Usaha Tani: [_________________]    │
│  Alamat: [_________________]        │
│  Nama Poktan: [_________________]   │
│  Alamat Poktan: [_________________] │
│  Kabupaten: [Dropdown]              │
│  Email: [_________________] (opt)   │
│  NIP: [_________________] (opt)     │
│  Password: [_________________]      │
│  Konfirmasi: [_________________]    │
│                                     │
│  [      DAFTAR      ]               │
└─────────────────────────────────────┘
```

### 8.3 Filament Admin Panel

#### Sidebar Navigation
```
┌────────────────────────┐
│  BAPELTAN ADMIN        │
│                        │
│  📊 Dashboard          │
│                        │
│  DATA                  │
│  ├── 👥 Peserta        │
│  ├── 🏛️ Kabupaten      │
│  ├── 📋 Kegiatan       │
│  ├── 📁 Kegiatan Type  │
│  ├── 👨‍👩‍👧 Group           │
│  ├── 📝 Registrasi     │
│  └── 📄 Zilenial       │
│                        │
│  EVALUASI              │
│  ├── 📊 Tipe Evaluasi  │
│  └── 📝 Evaluasi       │
│                        │
│  ⚙️ Pengaturan          │
│                        │
│  [Logout]              │
└────────────────────────┘
```

---

## 9. Keamanan

### 9.1 Authentication

| Aspek | Detail |
|-------|--------|
| **Password Hashing** | bcrypt (Laravel default) |
| **Session** | Database driver |
| **Remember Me** | Token-based (60 hari) |
| **CSRF Protection** | Laravel default (web routes) |

### 9.2 Authorization

| Aspek | Detail |
|-------|--------|
| **Role Check** | Middleware `Admin` dan `Peserta` |
| **Filament Panel** | `canAccessPanel()` method di User model |
| **Filament Resources** | Default policies (canView, canCreate, canEdit, canDelete) |

### 9.3 Input Validation

| Aspek | Detail |
|-------|--------|
| **NIK** | 16 digit, numeric, unique |
| **Email** | Valid email format, unique |
| **Kegiatan ID** | Must exist in kegiatans table |
| **Status** | Enum validation |
| **Date Range** | tanggal_selesai >= tanggal_mulai |

### 9.4 Rate Limiting ✅

| Endpoint | Rate Limit | Keterangan |
|----------|------------|------------|
| `POST /login` | 5/1 menit | Brute force protection ✅ |
| `GET /api/cek-nik` | 30/1 menit | Abuse prevention ✅ |
| `POST /api/daftar-pelatihan` | 10/1 menit | Spam prevention ✅ |

### 9.5 Security Best Practices

| # | Practice | Status |
|---|----------|--------|
| 1 | SQL Injection prevention (Eloquent ORM) | ✅ Built-in |
| 2 | XSS prevention (Blade escaping) | ✅ Built-in |
| 3 | CSRF token on forms | ✅ Built-in |
| 4 | Password hashing | ✅ Built-in |
| 5 | Rate limiting on auth | ✅ Selesai (Phase 1) |
| 6 | HTTPS enforcement | ⚠️ Perlu konfigurasi di production |
| 7 | Environment variable for secrets | ✅ Selesai (Phase 1) — `ADMIN_PASSWORD` |
| 8 | API authentication | ✅ Selesai (Phase 1) — API routes di `api.php` dengan throttle |

---

## 10. Roadmap Pengembangan

### Phase 1 — Stabilisasi & Keamanan (Minggu 1-2) ✅ SELESAI

| # | Task | Priority | Status | Keterangan |
|---|------|----------|--------|------------|
| 1.1 | Ganti password default admin, buat env variable | P1 | ✅ Selesai | `UserSeeder.php` gunakan `env('ADMIN_PASSWORD')` |
| 1.2 | Tambah rate limiting pada login (throttle:5,1) | P1 | ✅ Selesai | Route `login.perform` pakai `throttle:5,1` |
| 1.3 | Tambah rate limiting pada API routes | P1 | ✅ Selesai | `/cek-nik` throttle:30,1, `/daftar-pelatihan` throttle:10,1 |
| 1.4 | Set timezone `Asia/Jakarta` di config/app.php | P1 | ✅ Selesai | `config/app.php` timezone = `Asia/Jakarta` |
| 1.5 | Set locale `id` di config/app.php | P2 | ✅ Selesai | locale, fallback_locale, faker_locale = `id`/`id_ID` |
| 1.6 | Pindahkan API routes ke `routes/api.php` | P2 | ✅ Selesai | File baru `routes/api.php`, register di `bootstrap/app.php` |
| 1.7 | Perbaiki relasi Group model (hapus relasi ambigu) | P2 | ✅ Selesai | Hapus `kegiatans()` (hasMany), hanya `kegiatan()` (belongsTo) |
| 1.8 | Perbaiki unique constraint logic registrasi | P1 | ✅ Selesai | Tambah `kegiatan_type_id` + `tahun` di fillable & create logic |
| 1.9 | Hapus Tailwind CDN dari Blade views | P2 | ✅ Selesai | Ganti CDN script dengan `@vite('resources/css/app.css')` |
| 1.10 | Tambah autentikasi/CSRF pada API publik | P1 | ✅ Selesai | API routes di `api.php` (stateful middleware via bootstrap) |

**Deliverable**: Aplikasi lebih aman dan stabil. ✅

---

### Phase 2 — Fitur Inti (Minggu 3-5)

| # | Task | Priority | Estimasi |
|---|------|----------|----------|
| 2.1 | Dashboard Admin: widget statistik | P1 | 2 hari |
| 2.2 | Dashboard Peserta: ringkasan pelatihan | P1 | 2 hari |
| 2.3 | Manajemen User: CRUD user dengan role | P2 | 2 hari |
| 2.4 | Profil Peserta: edit profil sendiri | P2 | 2 hari |
| 2.5 | Notifikasi database untuk status registrasi | P2 | 2 hari |
| 2.6 | Refactor validasi ke Form Request classes | P2 | 2 hari |
| 2.7 | Tambahkan API Resources untuk response JSON | P3 | 1 hari |
| 2.8 | Perbaiki Filament resource panels | P2 | 2 hari |

**Deliverable**: Fitur inti lengkap dan berfungsi.

---

### Phase 3 — Fitur Pendukung (Minggu 6-8)

| # | Task | Priority | Estimasi |
|---|------|----------|----------|
| 3.1 | Manajemen Artikel: CRUD + model + migration | P2 | 3 hari |
| 3.2 | Manajemen Video: CRUD + model + migration | P2 | 2 hari |
| 3.3 | Manajemen Partner: CRUD + model + migration | P3 | 1 hari |
| 3.4 | Sertifikat Digital: template + generate PDF | P2 | 3 hari |
| 3.5 | Export Data: peserta/registrasi ke Excel | P2 | 2 hari |
| 3.6 | Activity Log: log semua aksi penting | P3 | 2 hari |
| 3.7 | Implementasi atau hapus RegistrasiZilenial | P3 | 1 hari |

**Deliverable**: Fitur pendukung lengkap.

---

### Phase 4 — Fitur Lanjutan (Minggu 9-12)

| # | Task | Priority | Estimasi |
|---|------|----------|----------|
| 4.1 | Riwayat Pelatihan: halaman untuk peserta | P2 | 2 hari |
| 4.2 | Monitoring Evaluasi: dashboard hasil evaluasi | P2 | 3 hari |
| 4.3 | Laporan: peserta per kabupaten, per jenis | P2 | 3 hari |
| 4.4 | Email Notification: status registrasi | P3 | 2 hari |
| 4.5 | REST API public untuk integrasi | P3 | 3 hari |
| 4.6 | Testing: unit test dan feature test | P1 | 3 hari |
| 4.7 | Deployment: setup production server | P1 | 2 hari |

**Deliverable**: Sistem siap production.

---

## 11. Issue & Tech Debt

### 11.1 Critical

| # | Issue | Lokasi | Solusi | Status |
|---|-------|--------|--------|--------|
| C1 | Password admin hardcoded `1234` | `UserSeeder.php` | Gunakan env variable `ADMIN_PASSWORD` | ✅ Selesai |
| C2 | API tanpa rate limiting | `routes/web.php` | Tambah `->middleware('throttle:30')` | ✅ Selesai |
| C3 | Login tanpa brute force protection | `SignInController` | Tambah `throttle:5,1` middleware | ✅ Selesai |
| C4 | API publik tanpa autentikasi | `/api/cek-nik`, `/api/daftar-pelatihan` | Minimal tambah CSRF atau API token | ✅ Selesai |

### 11.2 Moderate

| # | Issue | Lokasi | Solusi | Status |
|---|-------|--------|--------|--------|
| M1 | Relasi Group model ambigu | `Group.php` | Hapus salah satu relasi (belongsTo ATAU hasMany) | ✅ Selesai |
| M2 | Relasi ganda Peserta-Kegiatan | `Peserta.php` | Gunakan `belongsToMany` saja, hapus `hasMany` | ⏳ Phase 2 |
| M3 | Unique constraint vs logic mismatch | `registrasi_ulangs` vs `web.php:73` | Samakan logika validasi | ✅ Selesai |
| M4 | API routes di `web.php` | `routes/web.php` | Pindah ke `routes/api.php` | ✅ Selesai |
| M5 | Timezone `UTC`, locale `en` | `config/app.php` | Set `Asia/Jakarta` dan `id` | ✅ Selesai |

### 11.3 Minor

| # | Issue | Solusi | Status |
|---|-------|--------|--------|
| m1 | Tailwind CDN redundan di Blade views | Hapus CDN, Vite sudah handle | ✅ Selesai |
| m2 | Tidak ada Form Request classes | Buat untuk setiap validasi | ⏳ Phase 2 |
| m3 | Tidak ada API resources/transformers | Buat untuk response JSON konsisten | ⏳ Phase 2 |
| m4 | Tidak ada logging untuk aksi penting | Tambah activity log | ⏳ Phase 3 |
| m5 | `Pengaturan` tidak ada validasi input | Tambah validation rules | ⏳ Phase 2 |
| m6 | `RegistrasiZilenial` model kosong | Implementasi atau hapus | ⏳ Phase 3 |

---

## 12. Non-Functional Requirements

### 12.1 Performance

| Aspek | Target |
|-------|--------|
| Page Load Time | < 2 detik (3G connection) |
| API Response Time | < 500ms |
| Database Query | < 100ms per query |
| Concurrent Users | 100+ tanpa degradasi |

### 12.2 Scalability

| Aspek | Target |
|-------|--------|
| Database Records | 10.000+ peserta |
| File Storage | 1GB+ sertifikat |
| Horizontal Scaling | Load balancer ready |

### 12.3 Reliability

| Aspek | Target |
|-------|--------|
| Uptime | 99.5% |
| Backup | Daily automated backup |
| Recovery Time | < 1 jam |
| Data Integrity | Foreign key constraints |

### 12.4 Compatibility

| Aspek | Target |
|-------|--------|
| Browser | Chrome 90+, Firefox 88+, Safari 14+, Edge 90+ |
| Mobile | Responsive (min 320px width) |
| OS | Windows, macOS, Linux |

### 12.5 Maintainability

| Aspek | Target |
|-------|--------|
| Code Coverage | > 60% (Phase 4) |
| Documentation | PRD + API docs |
| Code Style | PSR-12, Laravel conventions |
| Version Control | Git (main branch) |

---

## 13. Glossary

| Istilah | Definisi |
|---------|----------|
| **Bapeltan** | Balai Pelatihan Pertanian |
| **Peserta** | Petani atau anggota poktan yang mengikuti pelatihan |
| **Poktan** | Kelompok Tani |
| **NIK** | Nomor Induk Kependudukan (16 digit) |
| **Kegiatan** | Pelatihan pertanian yang diselenggarakan Bapeltan |
| **Kegiatan Type** | Jenis pelatihan (Agribisnis Pangan, Perkebunan, Mekanisasi) |
| **Registrasi Ulang** | Pendaftaran peserta ke kegiatan pelatihan |
| **Zilenial** | (Belum didefinisikan) Fitur yang belum diimplementasi |
| **Evaluasi** | Ujian/pre-test/post-test terkait pelatihan |
| **Group** | Grup WhatsApp untuk komunikasi peserta pelatihan |
| **Pengaturan** | Konfigurasi umum aplikasi (judul, persyaratan, fasilitas) |

---

## Lampiran

### A. Enum Values

**users.role**: `admin`, `peserta`

**pesertas.agama**: `Islam`, `Kristen`, `Katolik`, `Hindu`, `Buddha`, `Konghucu`

**pesertas.jenis_kelamin**: `L` (Laki-laki), `P` (Perempuan)

**pesertas.status_pernikahan**: `Belum Menikah`, `Menikah`, `Cerai`

**pesertas.pendidikan_terakhir**: `SD`, `SMP`, `SMA`, `D3`, `S1`, `S2`, `S3`

**kegiatans.status**: `active`, `inactive`

**groups.status**: `active`, `inactive`

**registrasi_ulangs.status**: `pending`, `diterima`, `ditolak`, `selesai`

**evaluasi_questions.tipe_jawaban**: `radio`, `checkbox`, `scale`, `text`

### B. Seed Data

**Kabupaten** (30 entries): Temanggung, Magelang, Semarang, dan lainnya di Jawa Tengah.

**Kegiatan Type** (3 entries):
1. Agribisnis Tanaman Pangan
2. Agribisnis Perkebunan
3. Mekanisasi Pertanian

**Kegiatan** (5 entries): 5 pelatihan sample dengan kuota 30, status active, tanggal Oktober 2025.

**User Admin** (1 entry): Name: Admin, Email: bapeltan@gmail.com, Role: admin.

---

*End of Document*
