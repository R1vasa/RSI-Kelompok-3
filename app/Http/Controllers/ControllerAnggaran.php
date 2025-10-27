<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggaranBulanan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ControllerAnggaran extends Controller
{
    public function index()
    {
        $user = Auth::user();

        // Ambil semua anggaran milik user
        $anggaran = AnggaranBulanan::with('kategori')
            ->where('id_users', $user->id)
            ->get();

        // Ambil kategori untuk dropdown form
        $kategori = Kategori::all();

        return view('Pages.ManajemenAnggaran', compact('anggaran', 'kategori'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'jmlh_anggaran' => 'required|numeric|min:0',
            'periode' => 'required|date',
        ]);

        // 🔹 Format periode agar hanya berisi "YYYY-MM"
        $periode = Carbon::parse($request->periode)->format('Y-m');

        AnggaranBulanan::create([
            'id_users' => $user->id,
            'id_kategori' => $request->id_kategori,
            'jmlh_anggaran' => $request->jmlh_anggaran,
            'periode' => $periode,
        ]);

        return redirect()->back()->with('success', 'Anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'jmlh_anggaran' => 'required|numeric|min:0',
            'periode' => 'required|date'
        ]);

        $anggaran = AnggaranBulanan::findOrFail($id);

        // 🔹 Pastikan format periode juga "YYYY-MM" saat update
        $periode = Carbon::parse($request->periode)->format('Y-m');

        $anggaran->update([
            'id_kategori' => $request->id_kategori,
            'jmlh_anggaran' => $request->jmlh_anggaran,
            'periode' => $periode,
        ]);

        return redirect()->back()->with('success', 'Anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        AnggaranBulanan::findOrFail($id)->delete();
        return redirect()->back()->with('success', 'Anggaran berhasil dihapus.');
    }
}
