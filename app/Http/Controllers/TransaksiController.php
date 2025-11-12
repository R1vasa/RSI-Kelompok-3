<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\AnggaranBulanan;
use Carbon\Carbon;

class TransaksiController extends Controller
{
    public function index(Request $request) // 1. Tambahkan "Request $request"
    {
        // 2. Mulai query builder, JANGAN ->get() dulu
        $query = Transaksi::with('kategori')
            ->where('id_users', Auth::id());

        // 3. Terapkan filter JIKA ada input dari form

        // Filter berdasarkan Jenis Transaksi (pemasukan/pengeluaran)
        if ($request->filled('jenis_transaksi')) {
            // $request->jenis_transaksi akan berisi "pemasukan" atau "pengeluaran"
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // Filter berdasarkan Kategori
        if ($request->filled('kategori_id')) {
            // $request->kategori_id akan berisi id kategori (cth: 1, 2, 5)
            // Kolom di DB Anda adalah 'id_kategori' (berdasarkan method store)
            $query->where('id_kategori', $request->kategori_id);
        }

        // Filter berdasarkan Judul Transaksi (Search)
        if ($request->filled('search_judul')) {
            $query->where('judul_transaksi', 'like', '%' . $request->search_judul . '%');
        }

        // Filter berdasarkan Rentang Tanggal (Date Range)
        if ($request->filled('date_range')) {
            // $request->date_range akan berisi "YYYY-MM-DD to YYYY-MM-DD"
            $dates = explode(' to ', $request->date_range);

            // Pastikan formatnya benar (ada 2 tanggal)
            if (count($dates) == 2) {
                $startDate = $dates[0] . ' 00:00:00';
                $endDate = $dates[1] . ' 23:59:59';

                // Gunakan whereBetween untuk rentang tanggal
                $query->whereBetween('tgl_transaksi', [$startDate, $endDate]);
            }
        }

        // 4. Ambil semua data Kategori untuk dropdown filter
        // (Sama seperti di method create() Anda)
        $kategoris = Kategori::orderBy('id', 'asc')->get();

        // 5. Eksekusi query (setelah semua filter diterapkan)
        $transaksis = $query->orderBy('tgl_transaksi', 'desc')->get();

        // 6. Kirim KEDUA variabel ke view
        return view('pages/transaksi/transaksi', [
            'transaksis' => $transaksis,
            'kategoris' => $kategoris // Ini penting untuk @foreach di dropdown
        ]);
    }

    public function create()
    {
        // Ambil kategori dari tabel kategori (referensi)
        return view('pages/transaksi/transaksi_tambah', [
            'kategoris' => Kategori::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_transaksi' => 'required|string|max:40',
            'jumlah_transaksi' => 'required|numeric|min:1',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'tgl_transaksi' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id'
        ]);

        Transaksi::create([
            'id_users' => Auth::id(),
            'judul_transaksi' => $validated['judul_transaksi'],
            'jumlah_transaksi' => $validated['jumlah_transaksi'],
            'jenis_transaksi' => $validated['jenis_transaksi'],
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'id_kategori' => $validated['id_kategori']
        ]);

        if (strcasecmp($validated['jenis_transaksi'], 'Pengeluaran') === 0) {
            $bulan = Carbon::parse($validated['tgl_transaksi'])->format('Y-m');
            $kategoriId = $validated['id_kategori'];

            $totalPengeluaran = Transaksi::where('id_users', Auth::id())
                ->where('id_kategori', $kategoriId)
                ->where('jenis_transaksi', 'Pengeluaran')
                ->whereMonth('tgl_transaksi', Carbon::parse($bulan)->month)
                ->whereYear('tgl_transaksi', Carbon::parse($bulan)->year)
                ->sum('jumlah_transaksi');

            $anggaran = AnggaranBulanan::where('id_users', Auth::id())
                ->where('id_kategori', $kategoriId)
                ->where('periode', $bulan)
                ->first();

            if ($anggaran && $totalPengeluaran > $anggaran->jmlh_anggaran) {
                return redirect()->route('transaksi.index')
                    ->with('success', 'Transaksi berhasil disimpan.')
                    ->with('warning', '⚠️ Pengeluaran kategori ini telah melebihi anggaran bulan ini!');
            }
        }
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    public function edit($id)
    {
        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);
        $kategoris = \App\Models\Kategori::all();
        return view('pages/transaksi/transaksi_edit', compact('transaksi', 'kategoris'));
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate(
            [
                'judul_transaksi' => 'required|string|max:40',
                'jumlah_transaksi' => 'required|numeric|min:1',
                'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
                'tgl_transaksi' => 'required|date',
                'id_kategori' => 'required|exists:kategori,id'
            ],
            [
                'judul_transaksi' => 'judul transaksi harus diisi',
                'judul_transaksi' => 'judul transaksi maksimal 40 karakter',
                'jumlah_transaksi' => 'jumlah transaksi harus diisi angka positif',
            ]
        );

        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);
        $transaksi->update($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}
