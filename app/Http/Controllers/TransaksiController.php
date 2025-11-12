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
    /**
     * Menampilkan daftar transaksi user yang sedang login.
     * Dilengkapi fitur filter berdasarkan jenis, kategori, judul, dan rentang tanggal.
     */
    public function index(Request $request)
    {
        // Mulai query builder, belum eksekusi (belum ->get())
        $query = Transaksi::with('kategori')
            ->where('id_users', Auth::id());

        // 🔹 Filter berdasarkan Jenis Transaksi (Pemasukan atau Pengeluaran)
        if ($request->filled('jenis_transaksi')) {
            $query->where('jenis_transaksi', $request->jenis_transaksi);
        }

        // 🔹 Filter berdasarkan Kategori (id_kategori)
        if ($request->filled('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        // 🔹 Filter berdasarkan Judul Transaksi (fitur pencarian)
        if ($request->filled('search_judul')) {
            $query->where('judul_transaksi', 'like', '%' . $request->search_judul . '%');
        }

        // 🔹 Filter berdasarkan Rentang Tanggal (contoh format: "2025-01-01 to 2025-01-31")
        if ($request->filled('date_range')) {
            $dates = explode(' to ', $request->date_range);

            // Pastikan format rentang berisi dua tanggal valid
            if (count($dates) == 2) {
                $startDate = $dates[0] . ' 00:00:00';
                $endDate = $dates[1] . ' 23:59:59';
                $query->whereBetween('tgl_transaksi', [$startDate, $endDate]);
            }
        }

        // Ambil semua kategori (untuk dropdown filter di view)
        $kategoris = Kategori::orderBy('id', 'asc')->get();

        // Eksekusi query setelah semua filter diterapkan
        $transaksis = $query->orderBy('tgl_transaksi', 'desc')->get();

        // Kirim data transaksi dan kategori ke view
        return view('pages/transaksi/transaksi', [
            'transaksis' => $transaksis,
            'kategoris' => $kategoris
        ]);
    }

    /**
     * Menampilkan form tambah transaksi baru.
     */
    public function create()
    {
        // Ambil semua kategori untuk dropdown pilihan kategori
        return view('pages/transaksi/transaksi_tambah', [
            'kategoris' => Kategori::all()
        ]);
    }

    /**
     * Menyimpan transaksi baru ke database dan memeriksa apakah
     * pengeluaran melebihi anggaran bulanan.
     */
    public function store(Request $request)
    {
        // Validasi input dari form transaksi
        $validated = $request->validate([
            'judul_transaksi' => 'required|string|max:40',
            'jumlah_transaksi' => 'required|numeric|min:1',
            'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
            'tgl_transaksi' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id'
        ]);

        // Simpan data transaksi ke tabel
        Transaksi::create([
            'id_users' => Auth::id(),
            'judul_transaksi' => $validated['judul_transaksi'],
            'jumlah_transaksi' => $validated['jumlah_transaksi'],
            'jenis_transaksi' => $validated['jenis_transaksi'],
            'tgl_transaksi' => $validated['tgl_transaksi'],
            'id_kategori' => $validated['id_kategori']
        ]);

        // Jika transaksi adalah PENGELUARAN, periksa anggaran bulanan
        if (strcasecmp($validated['jenis_transaksi'], 'Pengeluaran') === 0) {
            // Ambil bulan dari tanggal transaksi (format YYYY-MM)
            $bulan = Carbon::parse($validated['tgl_transaksi'])->format('Y-m');
            $kategoriId = $validated['id_kategori'];

            // Hitung total pengeluaran user dalam bulan & kategori tersebut
            $totalPengeluaran = Transaksi::where('id_users', Auth::id())
                ->where('id_kategori', $kategoriId)
                ->where('jenis_transaksi', 'Pengeluaran')
                ->whereMonth('tgl_transaksi', Carbon::parse($bulan)->month)
                ->whereYear('tgl_transaksi', Carbon::parse($bulan)->year)
                ->sum('jumlah_transaksi');

            // Ambil data anggaran sesuai user, kategori, dan periode bulan
            $anggaran = AnggaranBulanan::where('id_users', Auth::id())
                ->where('id_kategori', $kategoriId)
                ->where('periode', $bulan)
                ->first();

            // Jika ada anggaran dan total pengeluaran melebihi jumlahnya, tampilkan peringatan
            if ($anggaran && $totalPengeluaran > $anggaran->jmlh_anggaran) {
                return redirect()->route('transaksi.index')
                    ->with('success', 'Transaksi berhasil disimpan.')
                    ->with('warning', '⚠️ Pengeluaran kategori ini telah melebihi anggaran bulan ini!');
            }
        }

        // Jika bukan pengeluaran atau masih dalam batas anggaran
        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil disimpan.');
    }

    /**
     * Menampilkan form untuk mengedit data transaksi.
     */
    public function edit($id)
    {
        // Ambil transaksi milik user berdasarkan ID
        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);
        $kategoris = Kategori::all();

        // Kirim data transaksi & kategori ke form edit
        return view('pages/transaksi/transaksi_edit', compact('transaksi', 'kategoris'));
    }

    /**
     * Memperbarui data transaksi yang sudah ada di database.
     */
    public function update(Request $request, $id)
    {
        // Validasi input edit transaksi
        $validated = $request->validate([
            'judul_transaksi' => 'required|string|max:40',
            'jumlah_transaksi' => 'required|numeric|min:1',
            'jenis_transaksi' => 'required|in:pemasukan,pengeluaran',
            'tgl_transaksi' => 'required|date',
            'id_kategori' => 'required|exists:kategori,id'
        ], [
            'judul_transaksi' => 'judul transaksi harus diisi',
            'judul_transaksi' => 'judul transaksi maksimal 40 karakter',
            'jumlah_transaksi' => 'jumlah transaksi harus diisi angka positif',
        ]);

        // Temukan transaksi milik user
        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);

        // Perbarui data transaksi sesuai input user
        $transaksi->update($validated);

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil diperbarui.');
    }

    /**
     * Menghapus transaksi dari database.
     */
    public function destroy($id)
    {
        // Ambil transaksi milik user berdasarkan ID
        $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);

        // Hapus transaksi dari tabel
        $transaksi->delete();

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil dihapus.');
    }
}

