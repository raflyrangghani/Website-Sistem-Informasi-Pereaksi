## Menjalankan Aplikasi
1. Pastikan server memiliki PHP dan Composer terinstal.
2. Untuk fitur ekspor/impor, setup queue worker:
   - Linux: Gunakan Supervisor (lihat bagian "Setup Supervisor" di bawah).
   - Windows: Gunakan Task Scheduler (lihat bagian "Setup Task Scheduler").
3. Deploy aplikasi ke server dan jalankan `php artisan serve` untuk pengujian awal.

### Setup Supervisor (Linux)
1. Install Supervisor: `sudo apt-get install supervisor`
2. Buat file konfigurasi: `sudo nano /etc/supervisor/conf.d/laravel-worker.conf`
3. Tambahkan:
   [program:laravel-worker]
   process_name=%(program_name)s_%(process_num)02d
   command=php /path/to/your/project/artisan queue:work --sleep=3 --tries=3
   autostart=true
   autorestart=true
   user=www-data
   numprocs=1
   redirect_stderr=true
   stdout_logfile=/path/to/your/project/storage/logs/worker.log
4. Jalankan: `sudo supervisorctl reread && sudo supervisorctl update && sudo supervisorctl start laravel-worker:*`

### Setup Task Scheduler (Windows)
1. Buka Task Scheduler.
2. Buat Task baru, set trigger setiap menit, dan action ke `php artisan queue:work --once`.
