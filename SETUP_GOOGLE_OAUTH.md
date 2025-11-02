# Setup Google OAuth untuk Login

## Langkah-langkah Setup:

### 1. Buat Google OAuth Credentials
1. Buka [Google Cloud Console](https://console.cloud.google.com/)
2. Buat project baru atau pilih project yang sudah ada
3. Enable Google+ API
4. Pergi ke **Credentials** > **Create Credentials** > **OAuth client ID**
5. Pilih **Web application**
6. Isi **Authorized redirect URIs** dengan:
   ```
   http://127.0.0.1:8000/auth/google/callback
   ```
   (Untuk production, ganti dengan domain Anda)
7. Copy **Client ID** dan **Client Secret**

### 2. Tambahkan ke file .env
Tambahkan baris berikut ke file `.env`:
```env
GOOGLE_CLIENT_ID=your-client-id-here
GOOGLE_CLIENT_SECRET=your-client-secret-here
GOOGLE_REDIRECT_URI=http://127.0.0.1:8000/auth/google/callback
```

### 3. Build Assets Vite (Opsional tapi Disarankan)
Untuk menghindari error Vite manifest, jalankan:
```bash
npm install
npm run build
```

Atau untuk development:
```bash
npm run dev
```

### 4. Clear Cache
```bash
php artisan config:clear
php artisan cache:clear
php artisan route:clear
```

## Setelah Setup:

1. Akses `http://127.0.0.1:8000`
2. Klik "Login dengan Google"
3. Setelah login, akan diarahkan ke halaman `/petugas`

## Catatan:
- Jika Vite belum di-build, aplikasi akan menggunakan Tailwind CDN sebagai fallback
- Pastikan callback URL di Google Console sama persis dengan yang di `.env`

