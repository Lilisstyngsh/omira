<?php
// Jalankan dari terminal: php hapus-tps.php
// Simpan file ini di root project (sejajar dengan artisan).
if (PHP_SAPI !== 'cli') {
    http_response_code(404);
    exit;
}
$root = __DIR__;
if (!is_file($root . '/artisan') || !is_file($root . '/routes/web.php')) {
    fwrite(STDERR, "Letakkan skrip ini di root project Laravel.\n");
    exit(1);
}
$routes = file_get_contents($root . '/routes/web.php');
if ($routes === false || str_contains($routes, 'TpsRepairController')) {
    fwrite(STDERR, "Salin routes/web.php dari paket revisi terlebih dahulu.\n");
    exit(1);
}
$files = [
    'app/Http/Controllers/TpsRepairController.php',
    'app/Models/TpsRepairOrder.php',
    'resources/views/user/tps/create.blade.php',
    'resources/views/user/tps/index.blade.php',
    'resources/views/user/tps/show.blade.php',
    'resources/views/omd/tps/index.blade.php',
    'resources/views/omd/tps/show.blade.php',
];
$failed = false;
foreach ($files as $relative) {
    $path = $root . '/' . $relative;
    if (!file_exists($path) && !is_link($path)) {
        echo "Sudah tidak ada: " . $relative . PHP_EOL;
        continue;
    }
    if ((!is_file($path) && !is_link($path)) || !unlink($path)) {
        fwrite(STDERR, "Gagal menghapus: " . $relative . PHP_EOL);
        $failed = true;
        continue;
    }
    echo "Dihapus: " . $relative . PHP_EOL;
}
if ($failed) exit(1);
echo "Selesai. Jalankan: php artisan optimize:clear" . PHP_EOL;
