# Backend Features - RS Sehat Selalu

## Fitur yang Sudah Dibuat

### 1. ✅ Autentikasi Petugas Loket
- **Login dengan Google OAuth**
  - Route: `GET /login` - Halaman login
  - Route: `GET /auth/google` - Redirect ke Google OAuth
  - Route: `GET /auth/google/callback` - Handle callback dari Google
  - Controller: `App\Http\Controllers\Auth\GoogleController`
  - Fitur:
    - Auto-create user jika belum ada (berdasarkan email)
    - Retry logic untuk menangani connection issues (5x retry)
    - Error handling yang baik
    - Auto-login setelah berhasil

### 2. ✅ Manajemen Loket (CRUD)
**Web Routes** (Protected by Auth):
- `GET /lokets` - Daftar semua loket
- `GET /lokets/create` - Form tambah loket
- `POST /lokets` - Simpan loket baru
- `GET /lokets/{id}` - Detail loket
- `GET /lokets/{id}/edit` - Form edit loket
- `PUT/PATCH /lokets/{id}` - Update loket
- `DELETE /lokets/{id}` - Hapus loket (dengan validasi jika masih ada antrian)

**API Routes**:
- `GET /api/lokets` - List lokets (JSON)
- `GET /api/lokets/{id}` - Get single loket (JSON)
- `POST /api/lokets` - Create loket (JSON)
- `PUT/PATCH /api/lokets/{id}` - Update loket (JSON)
- `DELETE /api/lokets/{id}` - Delete loket (JSON)

**Controller**: `App\Http\Controllers\LoketController`

**Validasi**:
- `code`: nullable, string, max:10, unique
- `nama_loket`: required, string, max:255
- `deskripsi`: nullable, string

### 3. ✅ Manajemen Antrian
**Web Routes** (Protected by Auth):
- `GET /antrians` - Daftar antrian (dengan filter loket_id, status)
- `POST /antrians/lokets/{loket_id}/generate` - Generate nomor antrian otomatis
- `PATCH /antrians/{id}/status` - Update status antrian
- `DELETE /antrians/{id}` - Hapus antrian

**API Routes**:
- `POST /api/lokets/{loket_id}/antrians` - Create antrian (auto-generate nomor)
- `GET /api/lokets/{loket_id}/waiting` - Get antrian menunggu untuk loket
- `PATCH /api/antrians/{id}/status` - Update status antrian
- `GET /api/antrians/current` - Get antrian yang sedang dipanggil
- `GET /api/antrians?loket_id=1&status=menunggu` - Get all antrian dengan filter

**Controller**: `App\Http\Controllers\AntrianController` & `App\Http\Controllers\API\AntrianController`

**Fitur Generate Nomor Antrian**:
- Auto-generate berdasarkan loket code + nomor urut
- Format: `{loket_code}{nomor_urut}` (contoh: A001, B001)
- Nomor reset setiap hari
- Memastikan nomor unik per hari

**Status Antrian**:
- `menunggu` - Antrian baru dibuat
- `dipanggil` - Sedang dipanggil (auto-set waktu_panggil)
- `selesai` - Antrian sudah selesai

### 4. ✅ Dashboard Petugas Loket
- Route: `GET /petugas` (Protected by Auth)
- Component: `App\Http\Livewire\PetugasLoket`
- Fitur:
  - Pilih loket
  - Lihat antrian yang sedang dipanggil
  - Lihat antrian menunggu
  - Panggil antrian
  - Selesai antrian

---

## API Endpoints Summary

### Loket API
```
GET    /api/lokets              - List semua loket
GET    /api/lokets/{id}         - Detail loket
POST   /api/lokets              - Buat loket baru
PUT    /api/lokets/{id}         - Update loket
PATCH  /api/lokets/{id}         - Update loket
DELETE /api/lokets/{id}         - Hapus loket
```

### Antrian API
```
POST   /api/lokets/{loket}/antrians     - Buat antrian (auto-generate nomor)
GET    /api/lokets/{loket}/waiting      - Antrian menunggu untuk loket
GET    /api/antrians/current            - Antrian sedang dipanggil
GET    /api/antrians?loket_id=1&status=menunggu  - List antrian dengan filter
PATCH  /api/antrians/{antrian}/status   - Update status antrian
```

---

## Contoh Penggunaan API

### 1. Create Loket
```bash
curl -X POST http://127.0.0.1:8000/api/lokets \
  -H "Content-Type: application/json" \
  -d '{
    "code": "A",
    "nama_loket": "Loket Umum",
    "deskripsi": "Loket untuk layanan umum"
  }'
```

### 2. Generate Antrian (Auto-generate nomor)
```bash
curl -X POST http://127.0.0.1:8000/api/lokets/1/antrians \
  -H "Content-Type: application/json"
```

### 3. Update Status Antrian
```bash
curl -X PATCH http://127.0.0.1:8000/api/antrians/1/status \
  -H "Content-Type: application/json" \
  -d '{
    "status": "dipanggil"
  }'
```

### 4. Get Waiting Antrians
```bash
curl http://127.0.0.1:8000/api/lokets/1/waiting
```

---

## Database Models

### Loket Model
- Fields: `id`, `code`, `nama_loket`, `deskripsi`, `timestamps`
- Relationships:
  - `hasMany(Antrian::class)` - Satu loket punya banyak antrian
  - `currentCalled()` - Antrian yang sedang dipanggil

### Antrian Model
- Fields: `id`, `loket_id`, `nomor_antrian`, `status`, `waktu_panggil`, `timestamps`
- Relationships:
  - `belongsTo(Loket::class)` - Antrian milik satu loket
- Status: `menunggu`, `dipanggil`, `selesai`

---

## Next Steps (Jika diperlukan)

1. **Middleware untuk Role/Permission** - Jika perlu membedakan akses admin vs petugas
2. **View untuk Management** - Buat view untuk CRUD Loket dan Antrian (saat ini hanya backend/API)
3. **Soft Delete** - Jika ingin menyimpan history antrian yang dihapus
4. **Laporan/Statistik** - Fitur laporan jumlah antrian per hari, per loket, dll
5. **Export Data** - Export antrian ke Excel/PDF
6. **Notifikasi Real-time** - Push notification saat antrian dipanggil

---

## Testing

### Test API dengan Postman atau cURL:
```bash
# Test create loket
curl -X POST http://127.0.0.1:8000/api/lokets \
  -H "Content-Type: application/json" \
  -d '{"code":"A","nama_loket":"Loket Umum"}'

# Test generate antrian
curl -X POST http://127.0.0.1:8000/api/lokets/1/antrians

# Test update status
curl -X PATCH http://127.0.0.1:8000/api/antrians/1/status \
  -H "Content-Type: application/json" \
  -d '{"status":"dipanggil"}'
```

---

## Catatan Penting

1. **Autentikasi**: Semua web routes protected dengan `auth` middleware
2. **API Routes**: Saat ini API routes tidak memerlukan autentikasi. Jika diperlukan, bisa tambahkan `auth:sanctum` atau `auth:api` middleware
3. **Error Handling**: Semua controller sudah memiliki error handling dan logging
4. **Validation**: Semua input sudah divalidasi dengan Laravel validation
5. **Auto-generate Nomor**: Sistem otomatis generate nomor antrian jika tidak disediakan

