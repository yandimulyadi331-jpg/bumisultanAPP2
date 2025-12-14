<?php
/**
 * Test SVG QR Code Generation
 * Run: php test_svg_qr.php
 */

require 'vendor/autoload.php';

use SimpleSoftwareIO\QrCode\Facades\QrCode;

try {
    echo "Testing SVG QR Code Generation...\n";
    
    // Test URL
    $testUrl = 'https://example.com/test';
    
    // Generate SVG QR Code
    $qrCode = QrCode::format('svg')
        ->size(300)
        ->margin(1)
        ->generate($testUrl);
    
    echo "✅ SVG QR Code generated successfully!\n";
    echo "Output length: " . strlen($qrCode) . " bytes\n";
    echo "First 100 chars: " . substr($qrCode, 0, 100) . "\n";
    
    // Try to save it
    @mkdir('public/storage/qr_codes', 0755, true);
    
    $filePath = 'public/storage/qr_codes/test_qr.svg';
    if (file_put_contents($filePath, $qrCode)) {
        echo "✅ SVG file saved successfully at: $filePath\n";
        echo "File size: " . filesize($filePath) . " bytes\n";
    } else {
        echo "❌ Failed to save SVG file\n";
    }
    
} catch (\Exception $e) {
    echo "❌ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString();
}
?>
