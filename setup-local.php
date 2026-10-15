<?php

// Prepare a fresh local demo without overwriting existing configuration.
chdir(__DIR__);
if (!is_file('artisan') || !is_file('.env.example')) {
    fwrite(STDERR, "Jalankan helper dari folder OMIRA yang berisi artisan.\n");
    exit(1);
}
if (is_file('.env')) {
    echo ".env yang sudah ada dipertahankan. Periksa konfigurasi database sebelum migrasi.\n";
    exit(0);
}
$configuration = file_get_contents('.env.example');
$configuration = preg_replace('/^DB_CONNECTION=.*$/m', 'DB_CONNECTION=sqlite', $configuration);
$configuration = preg_replace('/^DB_DATABASE=.*$/m', 'DB_DATABASE=database/database.sqlite', $configuration);
file_put_contents('.env', $configuration);
if (!is_file('database/database.sqlite')) {
    touch('database/database.sqlite');
}
echo "Konfigurasi SQLite lokal dan file database sudah disiapkan.\n";
echo "Lanjutkan: php artisan key:generate, lalu php artisan migrate --seed.\n";
