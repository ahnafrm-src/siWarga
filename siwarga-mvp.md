# SiWarga — MVP Document
> Sistem Informasi Warga RT 10

---

## 1. Gambaran Umum

**SiWarga** adalah aplikasi berbasis REST API untuk mengelola data warga RT 10. Dibangun dengan Laravel 11 sebagai backend API dan Blade + Tailwind CSS sebagai frontend client. Akses hanya untuk pengurus RT yang berwenang.

**Tujuan utama:**
- Menyimpan data kepala keluarga dan anggota keluarga secara terstruktur
- Mempermudah rekap data warga saat Idul Fitri
- Menyediakan dashboard ringkas kondisi warga RT

---

## 2. Stack Teknologi

| Kebutuhan | Teknologi |
|---|---|
| Backend | Laravel 11 (REST API) |
| Frontend | Blade + Tailwind CSS + Fetch/Axios |
| Database | MySQL |
| Export PDF | barryvdh/laravel-dompdf |
| Export Excel | maatwebsite/excel |
| Auth | Laravel Sanctum (token-based) |

---

## 3. Struktur Database

### Tabel `kepala_keluarga`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigIncrements | Primary Key |
| no_kk | char(16), nullable, unique | Nomor Kartu Keluarga |
| nama_kepala | varchar(100) | Nama kepala keluarga |
| alamat | text | Alamat rumah |
| no_hp | varchar(15), nullable | Nomor HP |
| created_at | timestamp | — |
| updated_at | timestamp | — |

### Tabel `anggota_keluarga`

| Kolom | Tipe | Keterangan |
|---|---|---|
| id | bigIncrements | Primary Key |
| kk_id | foreignId | FK → kepala_keluarga.id |
| nama | varchar(100) | Nama anggota |
| tanggal_lahir | date, nullable | Untuk hitung kelompok usia |
| jenis_kelamin | enum('L', 'P') | Laki-laki / Perempuan |
| hubungan_ke_kk | enum | Lihat nilai di bawah |
| created_at | timestamp | — |
| updated_at | timestamp | — |

**Nilai enum `hubungan_ke_kk`:**
`Kepala Keluarga`, `Istri`, `Anak`, `Orang Tua`, `Lainnya`

**Kelompok usia** tidak disimpan di database — dihitung otomatis via Laravel Accessor dari `tanggal_lahir`:

| Kelompok | Rentang Usia |
|---|---|
| Lansia | 60 tahun ke atas |
| Dewasa | 18 – 59 tahun |
| Remaja | 13 – 17 tahun |
| Anak-anak | 0 – 12 tahun |
| Tidak diketahui | tanggal_lahir null |

### Tabel `users` (bawaan Laravel)

Hanya untuk login pengurus. Tidak perlu registrasi publik. Seed 2 akun saja.

---

## 4. Relasi Antar Model

```
KepalaKeluarga → hasMany → AnggotaKeluarga
AnggotaKeluarga → belongsTo → KepalaKeluarga
```

---

## 5. Struktur Response API

Semua endpoint menggunakan format response yang konsisten:

**Sukses:**
```json
{
  "success": true,
  "message": "Pesan sukses",
  "data": { ... }
}
```

**Gagal / Error:**
```json
{
  "success": false,
  "message": "Pesan error",
  "data": null
}
```

---

## 6. Endpoint API

Base URL: `/api/v1` — semua endpoint dilindungi Sanctum middleware `auth:sanctum` kecuali login.

### Auth
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/v1/login` | Login, return token |
| POST | `/api/v1/logout` | Logout, revoke token |

### Dashboard
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/v1/dashboard` | Statistik warga RT |

### Kepala Keluarga
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/v1/kepala-keluarga` | List semua KK (+ search) |
| POST | `/api/v1/kepala-keluarga` | Tambah KK baru |
| GET | `/api/v1/kepala-keluarga/{id}` | Detail KK + anggota |
| PUT | `/api/v1/kepala-keluarga/{id}` | Edit KK |
| DELETE | `/api/v1/kepala-keluarga/{id}` | Hapus KK |

### Anggota Keluarga
| Method | Endpoint | Keterangan |
|---|---|---|
| POST | `/api/v1/kepala-keluarga/{kk_id}/anggota` | Tambah anggota |
| GET | `/api/v1/kepala-keluarga/{kk_id}/anggota/{id}` | Detail anggota |
| PUT | `/api/v1/kepala-keluarga/{kk_id}/anggota/{id}` | Edit anggota |
| DELETE | `/api/v1/kepala-keluarga/{kk_id}/anggota/{id}` | Hapus anggota |

### Export
| Method | Endpoint | Keterangan |
|---|---|---|
| GET | `/api/v1/export/pdf` | Download PDF rekap warga |
| GET | `/api/v1/export/excel` | Download Excel rekap warga |

**Query params filter export (opsional):**
- `kelompok_usia` — lansia / dewasa / remaja / anak-anak
- `jenis_kelamin` — L / P

---

## 7. Halaman Frontend (Blade + Fetch)

Frontend Blade berkomunikasi ke API via fetch/axios. Tidak ada form submission langsung ke server.

### 7.1 Login
- Form email + password
- Fetch ke `POST /api/v1/login`, simpan token di localStorage
- Redirect ke dashboard setelah login berhasil

### 7.2 Dashboard
**URL:** `/dashboard`

Fetch ke `GET /api/v1/dashboard`, tampilkan:

| Kartu | Isi |
|---|---|
| Total KK | Jumlah kepala keluarga terdaftar |
| Total Jiwa | Total semua anggota keluarga |
| Laki-laki | Jumlah anggota `jenis_kelamin = L` |
| Perempuan | Jumlah anggota `jenis_kelamin = P` |

Breakdown kelompok usia: Lansia, Dewasa, Remaja, Anak-anak, Tidak diketahui

### 7.3 Kepala Keluarga
**URL:** `/kepala-keluarga`

- Tabel: No KK, Nama Kepala, Alamat, No HP, Jumlah Anggota, Aksi
- Search by nama atau no KK
- Tombol: Tambah KK, Detail, Edit, Hapus

**Detail KK — URL:** `/kepala-keluarga/{id}`
- Informasi kepala keluarga
- Tabel anggota keluarga milik KK ini
- Tombol: Tambah Anggota, Edit Anggota, Hapus Anggota

### 7.4 Anggota Keluarga
Diakses dari halaman Detail KK — tidak punya halaman list tersendiri.

### 7.5 Export
**URL:** `/export`
- Filter kelompok usia dan jenis kelamin
- Tombol Export PDF dan Export Excel (hit endpoint API, trigger download)

**Kolom export Excel:**
No, No KK, Nama Kepala, Alamat, Nama Anggota, Tgl Lahir, Usia, Kelompok Usia, Jenis Kelamin, Hubungan ke KK

---

## 8. Struktur Folder Laravel

```
app/
├── Http/
│   ├── Controllers/Api/
│   │   ├── AuthController.php
│   │   ├── DashboardController.php
│   │   ├── KepalaKeluargaController.php
│   │   ├── AnggotaKeluargaController.php
│   │   └── ExportController.php
│   ├── Requests/
│   │   ├── StoreKepalaKeluargaRequest.php
│   │   └── StoreAnggotaKeluargaRequest.php
│   └── Resources/
│       ├── KepalaKeluargaResource.php
│       └── AnggotaKeluargaResource.php
├── Models/
│   ├── KepalaKeluarga.php
│   └── AnggotaKeluarga.php
└── Exports/
    └── WargaExport.php

resources/views/
├── layouts/
│   └── app.blade.php
├── dashboard.blade.php
├── kepala-keluarga/
│   ├── index.blade.php
│   └── show.blade.php
├── anggota/
│   └── (modal atau inline di show.blade.php)
└── export/
    ├── index.blade.php
    └── pdf.blade.php
```

---

## 9. Routes

```php
// Frontend (Blade)
Route::get('/login', ...)
Route::get('/dashboard', ...)
Route::get('/kepala-keluarga', ...)
Route::get('/kepala-keluarga/{id}', ...)
Route::get('/export', ...)

// API v1
Route::prefix('api/v1')->group(function () {

    // Auth (public)
    Route::post('/login', [AuthController::class, 'login']);

    // Protected
    Route::middleware('auth:sanctum')->group(function () {
        Route::post('/logout', [AuthController::class, 'logout']);
        Route::get('/dashboard', [DashboardController::class, 'index']);

        Route::apiResource('/kepala-keluarga', KepalaKeluargaController::class);
        Route::apiResource('/kepala-keluarga.anggota', AnggotaKeluargaController::class)
            ->except(['index']);

        Route::get('/export/pdf', [ExportController::class, 'pdf']);
        Route::get('/export/excel', [ExportController::class, 'excel']);
    });
});
```

---

## 10. Urutan Build

1. **Setup project** — konfigurasi `.env`, koneksi DB, install Sanctum
2. **Migration & Model** — buat tabel, definisikan relasi dan accessor `kelompok_usia`
3. **Auth API** — endpoint login/logout Sanctum, seed 2 user
4. **API Resource** — buat `KepalaKeluargaResource` dan `AnggotaKeluargaResource`
5. **Dashboard API** — query statistik, return JSON
6. **CRUD API Kepala Keluarga** — lengkap dengan search dan validasi
7. **CRUD API Anggota Keluarga** — nested di bawah KK
8. **Export** — install dompdf + maatwebsite/excel, endpoint export dengan filter
9. **Frontend Blade** — layout, halaman, fetch ke API
10. **Polish** — error handling konsisten, response format seragam

---

## 11. Catatan Penting

- `kelompok_usia` **tidak disimpan di DB**, hanya dihitung dari `tanggal_lahir` via accessor Laravel
- `no_kk` boleh kosong saat input awal, dilengkapi belakangan
- Tidak ada registrasi publik — akun dibuat via `php artisan db:seed`
- Token Sanctum disimpan di localStorage frontend, dikirim via header `Authorization: Bearer {token}`
- Data warga bersifat sensitif — pastikan aplikasi tidak dapat diakses publik
