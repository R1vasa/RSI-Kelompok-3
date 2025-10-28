<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\AnggaranBulanan;
use App\Models\Kategori;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class ControllerAnggaran extends Controller
{
    public function index(Request $request)
    {
        $user = Auth::user();

        $query = AnggaranBulanan::with('kategori')
            ->where('id_users', $user->id);

        if ($request->filled('kategori_id')) {
            $query->where('id_kategori', $request->kategori_id);
        }

        if ($request->filled('search')) {
            $keyword = $request->search;
            $query->whereHas('kategori', function ($q) use ($keyword) {
                $q->where('kategori', 'like', "%{$keyword}%");
            });
        }

        if ($request->filled('periode_filter')) {
            $periode = Carbon::parse($request->periode_filter)->format('Y-m');
            $query->where('periode', $periode);
        }

        $kategori = Kategori::orderBy('kategori', 'asc')->get();

        $periodeList = AnggaranBulanan::where('id_users', $user->id)
            ->select('periode')
            ->distinct()
            ->orderBy('periode', 'desc')
            ->pluck('periode');

        $anggaran = $query->orderBy('periode', 'desc')->get();

        return view('Pages.ManajemenAnggaran', compact('anggaran', 'kategori', 'periodeList'));
    }

    public function store(Request $request)
    {
        $user = Auth::user();

        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'jmlh_anggaran' => 'required|numeric|min:0',
            'periode' => 'required|date',
        ]);

        $periode = Carbon::parse($validated['periode'])->format('Y-m');

        $existing = AnggaranBulanan::where('id_users', $user->id)
            ->where('id_kategori', $validated['id_kategori'])
            ->where('periode', $periode)
            ->first();

        if ($existing) {
            return redirect()->back()->with('warning', '⚠️ Anggaran untuk kategori dan periode ini sudah ada.');
        }

        AnggaranBulanan::create([
            'id_users' => $user->id,
            'id_kategori' => $validated['id_kategori'],
            'jmlh_anggaran' => $validated['jmlh_anggaran'],
            'periode' => $periode,
        ]);

        return redirect()->route('anggaran.index')->with('success', '✅ Anggaran berhasil ditambahkan.');
    }

    public function update(Request $request, $id)
    {
        $validated = $request->validate([
            'id_kategori' => 'required|exists:kategori,id',
            'jmlh_anggaran' => 'required|numeric|min:0',
            'periode' => 'required|date',
        ]);

        $anggaran = AnggaranBulanan::where('id_users', Auth::id())->findOrFail($id);
        $periode = Carbon::parse($validated['periode'])->format('Y-m');

        $duplicate = AnggaranBulanan::where('id_users', Auth::id())
            ->where('id_kategori', $validated['id_kategori'])
            ->where('periode', $periode)
            ->where('id', '!=', $id)
            ->exists();

        if ($duplicate) {
            return redirect()->back()->with('warning', '⚠️ Anggaran untuk kategori dan periode ini sudah ada.');
        }

        $anggaran->update([
            'id_kategori' => $validated['id_kategori'],
            'jmlh_anggaran' => $validated['jmlh_anggaran'],
            'periode' => $periode,
        ]);

        return redirect()->route('anggaran.index')->with('success', '✅ Anggaran berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $anggaran = AnggaranBulanan::where('id_users', Auth::id())->findOrFail($id);
        $anggaran->delete();

        return redirect()->route('anggaran.index')->with('success', '🗑️ Anggaran berhasil dihapus.');
    }
}
