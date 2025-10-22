<?php

namespace App\Http\Controllers;

use App\Models\Transaksi;
use App\Models\Kategori;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TransaksiController extends Controller
{
    public function index()
    {
        // Ambil data transaksi + nama kategori dari relasi
        $transaksis = Transaksi::with('kategori')
            ->where('id_users', Auth::id())
            ->orderBy('tgl_transaksi', 'desc')
            ->get();

        return view('pages/transaksi', compact('transaksis'));
    }

    public function create()
    {
        // Ambil kategori dari tabel kategori (referensi)
        return view('pages/transaksi_tambah' , [
            'kategoris' => Kategori::all()
        ]);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_transaksi' => 'required|string|max:255',
            'jumlah_transaksi' => 'required|numeric|min:0',
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

        return redirect()->route('transaksi.index')->with('success', 'Transaksi berhasil ditambahkan.');
    }
    
    public function edit($id)
{
    $transaksi = Transaksi::where('id_users', Auth::id())->findOrFail($id);
    $kategoris = \App\Models\Kategori::all();
    return view('pages/transaksi_edit', compact('transaksi', 'kategoris'));
}

public function update(Request $request, $id)
{
    $validated = $request->validate([
        'judul_transaksi' => 'required|string|max:255',
        'jumlah_transaksi' => 'required|numeric|min:0',
        'jenis_transaksi' => 'required|in:Pemasukan,Pengeluaran',
        'tgl_transaksi' => 'required|date',
        'id_kategori' => 'required|exists:kategori,id'
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