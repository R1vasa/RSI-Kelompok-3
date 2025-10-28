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
    public function exportPDF(Request $request)
    {
        $query = Transaksi::with('kategori')
            ->where('id_users', Auth::id());

        $periodeAwal = null;
        $periodeAkhir = null;

        // Filter berdasarkan rentang tanggal
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);
            if (count($dates) == 2) {
                $periodeAwal = $dates[0];
                $periodeAkhir = $dates[1];

                $query->whereBetween('tgl_transaksi', [$periodeAwal, $periodeAkhir]);
            }
        }

        $transaksis = $query->orderBy('tgl_transaksi', 'desc')->get();

        $totalPemasukan = $transaksis->where('jenis_transaksi', 'pemasukan')->sum('jumlah_transaksi');
        $totalPengeluaran = $transaksis->where('jenis_transaksi', 'pengeluaran')->sum('jumlah_transaksi');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Siapkan nilai default jika tidak ada periode
        $periodeAwal = $periodeAwal ?? 'Semua';
        $periodeAkhir = $periodeAkhir ?? 'Periode';

        $pdf = Pdf::loadView('pages.laporan.pdf-laporan', [
            'transaksis' => $transaksis,
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir,
            'periodeAwal' => $periodeAwal,
            'periodeAkhir' => $periodeAkhir,
            'user' => Auth::user(),
        ]);

        // Laporan::create([
        //     'id_users' => Auth::user()->id,
        //     ''
        // ]);

        return $pdf->download('Laporan_Transaksi_' . now()->format('Ymd_His') . '.pdf');
    }

    public function forumPDF(Request $request, $slug)
    {
        $forum = ForumOrganisasi::where('slug', $slug)->firstOrFail();

        // Ambil periode dari query string (?periode=2025-01-01 to 2025-01-31)
        $periode = $request->input('periode');
        $dates = explode(' to ', $periode);

        $periodeAwal = isset($dates[0]) ? Carbon::parse($dates[0]) : Carbon::now()->startOfMonth();
        $periodeAkhir = isset($dates[1]) ? Carbon::parse($dates[1]) : Carbon::now()->endOfMonth();

        // Ambil transaksi berdasarkan id_forum & periode
        $transaksis = TransaksiOrganisasi::where('id_forum', $forum->id)
            ->whereBetween('tgl_transaksi', [$periodeAwal, $periodeAkhir])
            ->orderBy('tgl_transaksi', 'asc')
            ->get();

        // Hitung total pemasukan, pengeluaran, saldo
        $totalPemasukan = $transaksis->where('jenis', 'pemasukan')->sum('nominal');
        $totalPengeluaran = $transaksis->where('jenis', 'pengeluaran')->sum('nominal');
        $saldoAkhir = $totalPemasukan - $totalPengeluaran;

        // Render ke PDF
        $pdf = Pdf::loadView('pages.laporan.laporan_forum', [
            'forum' => $forum,
            'transaksis' => $transaksis,
            'periodeAwal' => $periodeAwal->translatedFormat('d M Y'),
            'periodeAkhir' => $periodeAkhir->translatedFormat('d M Y'),
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldoAkhir' => $saldoAkhir
        ])->setPaper('A4', 'portrait');

        $filename = 'Laporan_Forum_' . $forum->forum . '_' . now()->format('Ymd_His') . '.pdf';
        return $pdf->download($filename);
    }
}
