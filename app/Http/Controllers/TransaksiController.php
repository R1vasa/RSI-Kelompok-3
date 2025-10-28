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
    public function index()
    {
        // Ambil data transaksi + nama kategori dari relasi
        $transaksis = Transaksi::with('kategori')
            ->where('id_users', Auth::id())
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('pages/transaksi/transaksi', compact('transaksis'));
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
        $validated = $request->validate([
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
        ]);

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
