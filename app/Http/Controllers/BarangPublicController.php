<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use Illuminate\Http\Request;

class BarangPublicController extends Controller
{
    /**
     * Display detail barang secara publik berdasarkan QR Code hash
     * Halaman ini dapat diakses tanpa login
     * 
     * @param string $hash
     * @return \Illuminate\View\View|\Illuminate\Http\Response
     */
    public function publicDetail($hash)
    {
        // Cari barang berdasarkan qr_code_hash
        $barang = Barang::where('qr_code_hash', $hash)->firstOrFail();

        // Pastikan status barang adalah Aktif
        if ($barang->status_barang !== 'Aktif') {
            return response()->view('barang.public-not-available', [
                'message' => 'Barang tidak tersedia atau telah dihapus dari sistem.',
            ], 404);
        }

        // Load relasi yang diperlukan
        $barang->load('ruangan.gedung');

        return view('fasilitas.barang.public-detail', compact('barang'));
    }

    /**
     * Download QR Code sebagai file (SVG atau PNG)
     * 
     * @param string $hash
     * @return \Illuminate\Http\Response
     */
    public function downloadQrCode($hash)
    {
        $barang = Barang::where('qr_code_hash', $hash)->firstOrFail();

        if (empty($barang->qr_code_path) || !file_exists(public_path('storage/' . $barang->qr_code_path))) {
            return response()->json(['error' => 'QR Code tidak ditemukan'], 404);
        }

        // Determine file type based on extension
        $filePath = public_path('storage/' . $barang->qr_code_path);
        $fileExtension = pathinfo($filePath, PATHINFO_EXTENSION);
        
        return response()->download(
            $filePath,
            'qr_code_' . $barang->kode_barang . '.' . $fileExtension
        );
    }

    /**
     * API endpoint untuk mendapatkan detail barang dalam format JSON
     * 
     * @param string $hash
     * @return \Illuminate\Http\JsonResponse
     */
    public function getBarangDetails($hash)
    {
        $barang = Barang::where('qr_code_hash', $hash)
            ->with('ruangan.gedung')
            ->firstOrFail();

        if ($barang->status_barang !== 'Aktif') {
            return response()->json([
                'success' => false,
                'message' => 'Barang tidak tersedia atau telah dihapus dari sistem.',
            ], 404);
        }

        return response()->json([
            'success' => true,
            'data' => [
                'id' => $barang->id,
                'kode_barang' => $barang->kode_barang,
                'nama_barang' => $barang->nama_barang,
                'kategori' => $barang->kategori,
                'merk' => $barang->merk,
                'jumlah' => $barang->jumlah,
                'satuan' => $barang->satuan,
                'kondisi' => $barang->kondisi,
                'status_barang' => $barang->status_barang,
                'tanggal_perolehan' => $barang->tanggal_perolehan ? $barang->tanggal_perolehan->format('d-m-Y') : null,
                'harga_perolehan' => (string) $barang->harga_perolehan,
                'keterangan' => $barang->keterangan,
                'foto' => $barang->foto ? asset('storage/barang/' . $barang->foto) : null,
                'qr_code' => $barang->qr_code_path ? asset('storage/' . $barang->qr_code_path) : null,
                'ruangan' => [
                    'nama_ruangan' => $barang->ruangan->nama_ruangan,
                    'lantai' => $barang->ruangan->lantai,
                    'gedung' => [
                        'nama_gedung' => $barang->ruangan->gedung->nama_gedung,
                        'alamat' => $barang->ruangan->gedung->alamat,
                    ]
                ],
                'created_at' => $barang->created_at->format('d-m-Y H:i'),
                'updated_at' => $barang->updated_at->format('d-m-Y H:i'),
            ]
        ]);
    }
}
