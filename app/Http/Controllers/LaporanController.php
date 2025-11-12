<?php

namespace App\Http\Controllers;

use App\Models\ForumOrganisasi;
use App\Models\Transaksi;
use App\Models\Laporan;
use App\Models\TransaksiOrganisasi;
use Barryvdh\DomPDF\Facade\Pdf;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class LaporanController extends Controller
{
    /**
     * Mengekspor laporan transaksi pribadi user menjadi file PDF.
     * 
     * Fitur:
     * - Menampilkan data transaksi berdasarkan periode tanggal (optional).
     * - Menghitung total pemasukan, pengeluaran, dan saldo akhir.
     * - Menghasilkan file PDF dengan tampilan rapi menggunakan `dompdf`.
     */
    public function exportPDF(Request $request)
    {
        // Query transaksi user login
        $query = Transaksi::with('kategori')
            ->where('id_users', Auth::id());

        $periodeAwal = null;
        $periodeAkhir = null;

        // 🗓️ Filter berdasarkan rentang tanggal (format: YYYY-MM-DD to YYYY-MM-DD)
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $periodeAwal = $dates[0];
                $periodeAkhir = $dates[1];

                // Filter transaksi berdasarkan tanggal
                $query->whereBetween('tgl_transaksi', [$periodeAwal, $periodeAkhir]);
            }
        }

        // Ambil seluruh transaksi sesuai filter
        $transaksis = $query->orderBy('tgl_transaksi', 'desc')->get();

        // 💰 Hitung total berdasarkan jenis transaksi
        $totalPemasukan = $transaksis->where('jenis_transaksi', 'pemasukan')->sum('jumlah_transaksi');
        $totalPengeluaran = $transaksis->where('jenis_transaksi', 'pengeluaran')->sum('jumlah_transaksi');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Gunakan label default jika tidak ada periode dipilih
        $periodeAwal = $periodeAwal ?? 'Semua';
        $periodeAkhir = $periodeAkhir ?? 'Periode';

        // 📄 Buat file PDF menggunakan view
        $pdf = Pdf::loadView('pages.laporan.pdf-laporan', [
            'transaksis' => $transaksis,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'user' => Auth::user(),
        ]);

        // Nama file otomatis berdasarkan waktu unduh
        return $pdf->download('Laporan_Transaksi_' . now()->format('Ymd_His') . '.pdf');
    }

    /**
     * Mengekspor laporan transaksi forum organisasi dalam bentuk PDF.
     * 
     * Fitur:
     * - Berdasarkan slug forum yang aktif.
     * - Bisa difilter dengan periode (contoh: `?periode=2025-01-01 to 2025-01-31`).
     * - Menghitung total pemasukan, pengeluaran, dan saldo forum.
     * - Menghasilkan laporan keuangan forum dalam format PDF.
     */
    public function forumPDF(Request $request, $slug)
    {
        // 🔍 Ambil forum berdasarkan slug
        $forum = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        // Ambil parameter periode dari query string (?periode=YYYY-MM-DD to YYYY-MM-DD)
        $periode = $request->input('periode');
        $dates = explode(' to ', $periode);

        // Gunakan Carbon untuk parsing tanggal
        $periodeAwal = isset($dates[0]) ? Carbon::parse($dates[0]) : Carbon::now()->startOfMonth();
        $periodeAkhir = isset($dates[1]) ? Carbon::parse($dates[1]) : Carbon::now()->endOfMonth();

        // 📊 Ambil transaksi forum berdasarkan periode
        $transaksis = TransaksiOrganisasi::where('id_forum', $forum->id)
            ->whereBetween('tgl_transaksi', [$periodeAwal, $periodeAkhir])
            ->orderBy('tgl_transaksi', 'asc')
            ->get();

        // 💵 Hitung total pemasukan dan pengeluaran forum
        $totalPemasukan = $transaksis->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // 🧾 Generate file PDF dari view laporan_forum
        $pdf = Pdf::loadView('pages.laporan.laporan_forum', [
            'forum' => $forum,
            'transaksis' => $transaksis,
            'periodeAwal' => $periodeAwal->translatedFormat('d M Y'),
            'periodeAkhir' => $periodeAkhir->translatedFormat('d M Y'),
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir
        ])->setPaper('A4', 'portrait');

        // Nama file laporan (unik berdasarkan waktu dan nama forum)
        $filename = 'Laporan_Forum_' . $forum->forum . '_' . now()->format('Ymd_His') . '.pdf';

        return $pdf->download($filename);
    }
}
