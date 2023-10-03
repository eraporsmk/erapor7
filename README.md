<p align="center"><img src="http://app.erapor-smk.net/logo.png" width="600"></p>

## Server Requirements
PHP >= 8.1.0

Ctype PHP Extension

cURL PHP Extension

DOM PHP Extension

Fileinfo PHP Extension

Filter PHP Extension

Hash PHP Extension

Mbstring PHP Extension

OpenSSL PHP Extension

PCRE PHP Extension

PDO PHP Extension

Session PHP Extension

Tokenizer PHP Extension

XML PHP Extension


## Cara Install (Untuk Pengguna Baru)

- Clone Repositori ini
```bash
git clone https://github.com/eraporsmk/erapor7.git dataweb
cd dataweb
```

## Membuat file .env
```bash
cp .env.example .env
nano .env
```


- Koneksi Database
```bash
DB_HOST=127.0.0.1
DB_PORT=5432
DB_DATABASE=db_name
DB_USERNAME=db_user
DB_PASSWORD=db_pass
```

- Install Dependencies
```bash
composer install
```


## Generate App Key
```bash
php artisan key:generate
```

## Migration
- Membuat struktur table
```bash
php artisan migrate
```

- Jalankan seeder
```bash
php artisan db:seed
```
## Untuk pengguna windows:
- Panduan aplikasi & installer. silahkan download [disini](http://erapor.ditpsmk.net/pusat-unduhan)

## Cara Install (Untuk Pengguna Lama)

- Clone Repositori ini
```bash
git clone https://github.com/eraporsmk/erapor7.git dataweb
cd dataweb
```

## Copy file .env
Copy file .env dari root folder aplikasi versi 6xx ke root folder aplikasi versi 7xx

- Install Dependencies
```bash
composer install
```

## Update Versi Aplikasi
```bash
php artisan erapor:update
```

## Edit file .env untuk menampilkan foto profile
```APP_URL=http://localhost:8154```

Sesuaikan dengan alamat/domain yang dipakai

Kemudian tambah kode dibawah ini agar laman register tidak tersedia

```REGISTRATION=false```

## Catatan khusus pengguna windows:
- Konfigurasi koneksi database seperti dibawah ini
```
DB_CONNECTION=pgsql
DB_HOST=127.0.0.1

DB_PORT=58154
DB_DATABASE=windows
DB_USERNAME=windows
DB_PASSWORD=windows
```

## Catatan khusus untuk pengguna lama (ALL OS):
Untuk mengambil gambar/foto/logo yang telah di upload di aplikasi versi sebelumnya, silahkan copy dari aplikasi lama di folder storage/public, kemudian paste di aplikasi baru di folder storage/public

## Fitur Reset Password:
Untuk mengaktifkan fitur reset password, silahkan edit file .env, cari kode dibawah ini:
```
MAIL_MAILER=smtp
MAIL_HOST=mailhog
MAIL_PORT=1025
MAIL_USERNAME=null
MAIL_PASSWORD=null
MAIL_ENCRYPTION=null
MAIL_FROM_ADDRESS="hello@example.com"
MAIL_FROM_NAME="${APP_NAME}"
```

Kemudian ganti dengan kode ini:
```
MAIL_MAILER=smtp
MAIL_HOST=smtp.gmail.com
MAIL_PORT=587
MAIL_USERNAME=eraporsmk@gmail.com
MAIL_PASSWORD="twvv dabv jimx mykw"
MAIL_ENCRYPTION=ssl
MAIL_FROM_ADDRESS=eraporsmk@gmail.com
MAIL_FROM_NAME="${APP_NAME}"
```

Kemudian simpan perubahan file .env lalu jalankan:
```
php artisan config:clear
```
Catatan: Tidak perlu merubah apapun, copy paste sesuai yang tertera di deskripsi

## Contributing

1. Fork it (<https://github.com/eraporsmk/erapor7/fork>)
2. Create your feature branch (`git checkout -b feature/fooBar`)
3. Commit your changes (`git commit -am 'Add some fooBar'`)
4. Push to the branch (`git push origin feature/fooBar`)
5. Create a new Pull Request
