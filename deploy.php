<?php
/**
 * DEPLOYMENT SCRIPT untuk Update File ke Hosting
 * 
 * Cara pakai:
 * 1. Upload file ini ke folder root aplikasi di hosting
 * 2. Akses: http://domainanda.com/deploy.php?key=SECRET_KEY
 * 3. Tunggu proses selesai
 */

// GANTI dengan key rahasia Anda!
$SECRET_KEY = 'bumisultan_deploy_2025';
$provided_key = $_GET['key'] ?? '';

// Validate key
if ($provided_key !== $SECRET_KEY) {
    http_response_code(403);
    die('❌ Unauthorized! Invalid deploy key.');
}

echo "🚀 BUMISULTAN DEPLOYMENT SCRIPT\n";
echo "================================\n\n";

// List of files to verify/check
$files_to_check = [
    'app/Services/QrCodeBarangService.php',
    'app/Http/Controllers/BarangPublicController.php',
    'resources/views/fasilitas/barang/index.blade.php',
    'resources/views/fasilitas/barang/public-detail.blade.php',
    'routes/web.php',
    'app/Models/Barang.php',
    'app/Http/Controllers/BarangController.php',
];

echo "📋 Checking files...\n";
$all_exist = true;
foreach ($files_to_check as $file) {
    $exists = file_exists($file);
    $status = $exists ? '✅' : '❌';
    echo "$status $file\n";
    if (!$exists) $all_exist = false;
}

echo "\n";

if ($all_exist) {
    echo "✅ All files present!\n\n";
} else {
    echo "❌ Some files are missing!\n\n";
}

// Run artisan commands
echo "🔧 Running deployment commands...\n";
echo "================================\n\n";

$commands = [
    'cache:clear' => 'Clearing cache',
    'view:clear' => 'Clearing views',
    'route:clear' => 'Clearing routes',
    'config:clear' => 'Clearing config',
];

foreach ($commands as $cmd => $desc) {
    echo "⏳ $desc... ";
    passthru("php artisan $cmd 2>&1", $return);
    echo $return === 0 ? "✅\n" : "⚠️\n";
}

echo "\n";

// Check migrations
echo "📊 Checking migrations...\n";
passthru('php artisan migrate:status 2>&1');

echo "\n================================\n";
echo "✅ DEPLOYMENT COMPLETE!\n";
echo "================================\n\n";

echo "📝 Next steps:\n";
echo "1. Test the application: http://domainanda.com/fasilitas/asset\n";
echo "2. Check error logs: storage/logs/laravel.log\n";
echo "3. Verify QR codes: php artisan check:barang-qr\n";
echo "4. Delete this file: deploy.php\n";

?>
