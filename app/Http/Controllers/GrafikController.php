<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;

class GrafikController extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();
        $bulan = $request->input('bulan', date('m'));
        $tahun = $request->input('tahun', date('Y'));

        // Ambil daftar tahun yang punya transaksi
        $tahunTersedia = Transaksi::where('id_users', $user->id)
            ->selectRaw('YEAR(tgl_transaksi) as tahun')
            ->distinct()
            ->orderBy('tahun', 'desc')
            ->pluck('tahun');

        // Ambil semua transaksi user untuk bulan & tahun yang dipilih
        $transaksi = Transaksi::with('kategori')
            ->where('id_users', $user->id)
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        // Jika tidak ada transaksi bulan itu
        if ($transaksi->isEmpty()) {
            return view('Pages.TrenPage', [
                'labelsPemasukan' => [],
                'dataPemasukan' => [],
                'labelsPengeluaran' => [],
                'dataPengeluaran' => [],
                'detailPemasukan' => [],
                'detailPengeluaran' => [],
                'labelsHarian' => [],
                'dataPemasukanHarian' => [],
                'dataPengeluaranHarian' => [],
                'bulan' => $bulan,
                'tahun' => $tahun,
                'tahunTersedia' => $tahunTersedia,
                'message' => 'Tidak ada transaksi pada bulan ini.'
            ]);
        }

        // Kelompokkan data pemasukan
        $pemasukan = $transaksi->where('jenis_transaksi', 'pemasukan')
            ->groupBy('id_kategori')
            ->map(fn($items) => $items->sum('jumlah_transaksi'));

        // Kelompokkan data pengeluaran
        $pengeluaran = $transaksi->where('jenis_transaksi', 'pengeluaran')
            ->groupBy('id_kategori')
            ->map(fn($items) => $items->sum('jumlah_transaksi'));

        // Ambil label kategori
        $labelsPemasukan = [];
        $detailPemasukan = [];
        foreach ($pemasukan as $idKategori => $jumlah) {
            $kategori = Kategori::find($idKategori);
            $nama = $kategori ? $kategori->kategori : 'Tidak Diketahui';
            $labelsPemasukan[] = $nama;
            $detailPemasukan[] = ['nama' => $nama, 'jumlah' => $jumlah];
        }

        $labelsPengeluaran = [];
        $detailPengeluaran = [];
        foreach ($pengeluaran as $idKategori => $jumlah) {
            $kategori = Kategori::find($idKategori);
            $nama = $kategori ? $kategori->kategori : 'Tidak Diketahui';
            $labelsPengeluaran[] = $nama;
            $detailPengeluaran[] = ['nama' => $nama, 'jumlah' => $jumlah];
        }

        // Hitung total per hari untuk line chart
        $harian = collect(range(1, cal_days_in_month(CAL_GREGORIAN, $bulan, $tahun)))->map(function ($hari) use ($transaksi, $bulan, $tahun) {
            $tanggal = sprintf('%04d-%02d-%02d', $tahun, $bulan, $hari);

            $pemasukan = $transaksi
                ->where('jenis_transaksi', 'pemasukan')
                ->where('tgl_transaksi', $tanggal)
                ->sum('jumlah_transaksi');

            $pengeluaran = $transaksi
                ->where('jenis_transaksi', 'pengeluaran')
                ->where('tgl_transaksi', $tanggal)
                ->sum('jumlah_transaksi');

            return [
                'tanggal' => $hari,
                'pemasukan' => $pemasukan,
                'pengeluaran' => $pengeluaran,
            ];
        });

        $labelsHarian = $harian->pluck('tanggal');
        $dataPemasukanHarian = $harian->pluck('pemasukan');
        $dataPengeluaranHarian = $harian->pluck('pengeluaran');

        return view('Pages.TrenPage', [
            'labelsPemasukan' => $labelsPemasukan,
            'dataPemasukan' => $pemasukan->values(),
            'labelsPengeluaran' => $labelsPengeluaran,
            'dataPengeluaran' => $pengeluaran->values(),
            'detailPemasukan' => $detailPemasukan,
            'detailPengeluaran' => $detailPengeluaran,
            'labelsHarian' => $labelsHarian,
            'dataPemasukanHarian' => $dataPemasukanHarian,
            'dataPengeluaranHarian' => $dataPengeluaranHarian,
            'bulan' => $bulan,
            'tahun' => $tahun,
            'tahunTersedia' => $tahunTersedia,
            'message' => null
        ]);
    }

    private function getChartData($bulan, $tahun)
    {
        $user = Auth::user();

        $transaksi = Transaksi::with('kategori')
            ->where('id_users', $user->id)
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->get();

        $totalPemasukan = $transaksi->where('jenis_transaksi', 'pemasukan')->sum('jumlah_transaksi');
        $totalPengeluaran = $transaksi->where('jenis_transaksi', 'pengeluaran')->sum('jumlah_transaksi');

        $saldo = $totalPemasukan - $totalPengeluaran;

        return [
            'totalPemasukan' => $totalPemasukan,
            'totalPengeluaran' => $totalPengeluaran,
            'saldo' => $saldo
        ];
    }
    public function dashboard(Request $request)
    {
        // Ambil bulan & tahun (default bulan ini)
        $bulan = $request->bulan ?? date('m');
        $tahun = $request->tahun ?? date('Y');

        // Panggil ulang logic yang sama seperti grafik index
        $chartData = $this->getChartData($bulan, $tahun);

        return view('Pages.Dashboard', $chartData + [
            'bulan' => $bulan,
            'tahun' => $tahun
        ]);
    }
}
