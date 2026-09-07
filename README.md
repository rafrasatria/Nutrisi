---
title: Detektif Nutrisi
colorFrom: red
colorTo: brown
sdk: docker
app_port: 7860
---

# Detektif Nutrisi — Kuis Makanan & Minuman (Laravel)

Media pembelajaran nutrisi: siswa mengerjakan 7 soal + 5 berkas misi (Lab Mi, Mencocokkan, Materi & Kuis Organ, Susun Jalur), isi kesimpulan + refleksi, unduh PDF. Guru memantau per kelas + rekap PDF.

## Cara menjalankan (untuk orang lain)

Syarat: PHP 8.3+, Composer, MySQL (mis. Laragon/XAMPP).

```
git clone https://github.com/rafrasatria/Nutrisi.git
cd Nutrisi
composer install
copy .env.example .env
php artisan key:generate
```

Edit `.env`: `DB_DATABASE=nutrisi` (buat database kosong dulu) + user/password MySQL masing-masing, lalu:

```
php artisan migrate --seed
php artisan serve --host=0.0.0.0 --port=8000
```

Buka `http://localhost:8000` di laptop itu. Akun coba: siswa `siswa1 / siswa123`, guru: pilih kelas + `guru123`.

Perangkat lain se-WiFi membuka `http://IP-LAPTOP:8000` (cek IP lewat `ipconfig` → Wireless LAN adapter WiFi → IPv4 Address).

Kalau HP/teman tidak bisa membuka padahal laptop bisa:
1. Pastikan perintah serve memakai `--host=0.0.0.0` (di terminal harus tertulis `Server running on [http://0.0.0.0:8000]`).
2. Pastikan HP dan laptop di WiFi yang sama, dan IP belum berubah (cek `ipconfig` lagi).
3. Izinkan di Windows Firewall (popup "Allow access" untuk PHP, atau izinkan port 8000 TCP).
4. Kalau laptop bisa dibuka lewat IP tapi HP tetap tidak bisa, berarti WiFi-nya memblokir antar-perangkat (umum di WiFi kampus/kantor) — solusinya laptop ikut hotspot HP, lalu ulangi cek IP.

<p align="center"><a href="https://laravel.com" target="_blank"><img src="https://raw.githubusercontent.com/laravel/art/master/logo-lockup/5%20SVG/2%20CMYK/1%20Full%20Color/laravel-logolockup-cmyk-red.svg" width="400" alt="Laravel Logo"></a></p>

<p align="center">
<a href="https://github.com/laravel/framework/actions"><img src="https://github.com/laravel/framework/workflows/tests/badge.svg" alt="Build Status"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/dt/laravel/framework" alt="Total Downloads"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/v/laravel/framework" alt="Latest Stable Version"></a>
<a href="https://packagist.org/packages/laravel/framework"><img src="https://img.shields.io/packagist/l/laravel/framework" alt="License"></a>
</p>

## About Laravel

Laravel is a web application framework with expressive, elegant syntax. We believe development must be an enjoyable and creative experience to be truly fulfilling. Laravel takes the pain out of development by easing common tasks used in many web projects, such as:

- [Simple, fast routing engine](https://laravel.com/docs/routing).
- [Powerful dependency injection container](https://laravel.com/docs/container).
- Multiple back-ends for [session](https://laravel.com/docs/session) and [cache](https://laravel.com/docs/cache) storage.
- Expressive, intuitive [database ORM](https://laravel.com/docs/eloquent).
- Database agnostic [schema migrations](https://laravel.com/docs/migrations).
- [Robust background job processing](https://laravel.com/docs/queues).
- [Real-time event broadcasting](https://laravel.com/docs/broadcasting).

Laravel is accessible, powerful, and provides tools required for large, robust applications.

## Learning Laravel

Laravel has the most extensive and thorough [documentation](https://laravel.com/docs) and video tutorial library of all modern web application frameworks, making it a breeze to get started with the framework.

In addition, [Laracasts](https://laracasts.com) contains thousands of video tutorials on a range of topics including Laravel, modern PHP, unit testing, and JavaScript. Boost your skills by digging into our comprehensive video library.

You can also watch bite-sized lessons with real-world projects on [Laravel Learn](https://laravel.com/learn), where you will be guided through building a Laravel application from scratch while learning PHP fundamentals.

## Agentic Development

Laravel's predictable structure and conventions make it ideal for AI coding agents like Claude Code, Cursor, and GitHub Copilot. Install [Laravel Boost](https://laravel.com/docs/ai) to supercharge your AI workflow:

```bash
composer require laravel/boost --dev

php artisan boost:install
```

Boost provides your agent 15+ tools and skills that help agents build Laravel applications while following best practices.

## Contributing

Thank you for considering contributing to the Laravel framework! The contribution guide can be found in the [Laravel documentation](https://laravel.com/docs/contributions).

## Code of Conduct

In order to ensure that the Laravel community is welcoming to all, please review and abide by the [Code of Conduct](https://laravel.com/docs/contributions#code-of-conduct).

## Security Vulnerabilities

If you discover a security vulnerability within Laravel, please send an e-mail to Taylor Otwell via [taylor@laravel.com](mailto:taylor@laravel.com). All security vulnerabilities will be promptly addressed.

## License

The Laravel framework is open-sourced software licensed under the [MIT license](https://opensource.org/licenses/MIT).
