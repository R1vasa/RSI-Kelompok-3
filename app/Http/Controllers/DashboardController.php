<?php

namespace App\Http\Controllers;

use Illuminate\Support\Facades\Auth;
use App\Models\Transaksi;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $userId = Auth::id();
        $bulan = now()->month;
        $tahun = now()->year;

        // Total bulanan
        $pemasukanBulan = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pemasukan')
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->sum('jumlah_transaksi');

        $pengeluaranBulan = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->whereMonth('tgl_transaksi', $bulan)
            ->whereYear('tgl_transaksi', $tahun)
            ->sum('jumlah_transaksi');

        $saldo = $pemasukanBulan - $pengeluaranBulan;

        // ✅ Tambahkan pemasukan & pengeluaran hari ini
        $today = Carbon::today();

        $todayIncome = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pemasukan')
            ->whereDate('tgl_transaksi', $today)
            ->sum('jumlah_transaksi');

        $todayExpense = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->whereDate('tgl_transaksi', $today)
            ->sum('jumlah_transaksi');

        // Grafik 30 hari terakhir
        $grafik = Transaksi::where('id_users', $userId)
            ->whereBetween('tgl_transaksi', [now()->subDays(30), now()])
            ->selectRaw('DATE(tgl_transaksi) as tanggal, SUM(jumlah_transaksi) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get();
        
            
        $weeklyData = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pemasukan')
            ->whereBetween('tgl_transaksi', [now()->subDays(6), now()])
            ->selectRaw('DATE(tgl_transaksi) as tanggal, SUM(jumlah_transaksi) as total')
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->get()
            ->pluck('total', 'tanggal')
            ->toArray();

        $totalIncome = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pemasukan')
            ->sum('jumlah_transaksi');

        $totalExpense = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->sum('jumlah_transaksi');
        
        $weeklyDates = collect([]);
for ($i = 6; $i >= 0; $i--) {
    $weeklyDates->push(now()->subDays($i)->format('Y-m-d'));
}

        // Ambil data pemasukan asli
        $rawWeeklyIncome = Transaksi::where('id_users', $userId)
            ->where('jenis_transaksi', 'Pemasukan')
            ->whereBetween('tgl_transaksi', [now()->subDays(6), now()])
            ->selectRaw('DATE(tgl_transaksi) as tanggal, SUM(jumlah_transaksi) as total')
            ->groupBy('tanggal')
            ->pluck('total', 'tanggal');

        // Cocokkan dengan array 7 hari, isi 0 jika tidak ada data
        $weeklyIncomeData = $weeklyDates->map(function ($date) use ($rawWeeklyIncome) {
            return $rawWeeklyIncome[$date] ?? 0;
        });
        
        $weeklyIncomeTotal = array_sum($weeklyData);

        $weeklyExpenseData = DB::table('transaksi')
            ->select(DB::raw('DATE(tgl_transaksi) as tanggal'), DB::raw('SUM(jumlah_transaksi) as total'))
            ->where('id_users', $userId)
            ->where('jenis_transaksi', 'Pengeluaran')
            ->where('tgl_transaksi', '>=', now()->subDays(6))
            ->groupBy('tanggal')
            ->orderBy('tanggal')
            ->pluck('total')
            ->toArray();
        
        $weeklyExpenseData = array_pad($weeklyExpenseData, 7, 0);
        $weeklyExpenseTotal = array_sum($weeklyExpenseData);
    


        return view('pages.Dashboard', compact(
            'pemasukanBulan', 
            'pengeluaranBulan', 
            'saldo', 
            'grafik',
            'todayIncome',
            'todayExpense',
            'weeklyData',
            'totalIncome',
            'totalExpense',
            'weeklyIncomeTotal',
            'weeklyExpenseTotal',
            'weeklyIncomeData'
        ));
    }
}
