<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use SimpleSoftwareIO\QrCode\Facades\QrCode;

class TestSvgQrCode extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'test:svg-qr';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Test SVG QR Code generation without imagick';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Testing SVG QR Code Generation...');

        try {
            // Test URL
            $testUrl = 'https://example.com/test';

            // Generate SVG QR Code
            $qrCode = QrCode::format('svg')
                ->size(300)
                ->margin(1)
                ->generate($testUrl);

            $this->info('✅ SVG QR Code generated successfully!');
            $this->info('Output length: ' . strlen($qrCode) . ' bytes');
            $this->info('First 100 chars: ' . substr($qrCode, 0, 100));

            // Try to save it
            @mkdir(public_path('storage/qr_codes'), 0755, true);

            $filePath = 'storage/qr_codes/test_qr.svg';
            if (file_put_contents(public_path($filePath), $qrCode)) {
                $this->info('✅ SVG file saved successfully at: ' . public_path($filePath));
                $this->info('File size: ' . filesize(public_path($filePath)) . ' bytes');
            } else {
                $this->error('❌ Failed to save SVG file');
            }

            return 0;
        } catch (\Exception $e) {
            $this->error('❌ Error: ' . $e->getMessage());
            $this->error('Stack trace: ' . $e->getTraceAsString());
            return 1;
        }
    }
}
