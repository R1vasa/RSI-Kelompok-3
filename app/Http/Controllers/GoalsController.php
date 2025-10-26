<?php

namespace App\Http\Controllers;

use App\Models\Goals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tabungan;

class GoalsController extends Controller
{
    public function index()
    {
        $goals = Goals::where('id_users', Auth::id())->get();

        foreach ($goals as $goal) {
            $goal->status = $goal->current_amount >= $goal->jumlah_target
                ? 'Tercapai'
                : 'Belum Tercapai';
        }

        return view('pages/goals/goals', compact('goals'));
    }

    public function create()
    {
        return view('pages/goals/goals_tambah');
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul_goals' => 'required|string|max:40',
            'jumlah_target' => 'required|numeric|min:1',
            'tgl_target' => 'required|date|after_or_equal:today',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
             'judul_goals' => 'Judul goals maksimal 40 karakter',
            'jumlah_target' => 'Jumlah target harus diisi angka positif',
            'tgl_target' => 'Tanggal target tidak boleh kurang dari tanggal sekarang',
            'gambar' => 'Gambar harus berupa file gambar (jpg, jpeg, png)',
            'gambar' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Simpan gambar (jika ada)
        $gambarPath = $request->hasFile('gambar')
            ? $request->file('gambar')->store('goals', 'public')
            : null;

        Goals::create([
            'id_users' => Auth::id(),
            'judul_goals' => $validated['judul_goals'],
            'jumlah_target' => $validated['jumlah_target'],
            'tgl_target' => $validated['tgl_target'],
            'current_amount' => 0,
            'gambar' => $gambarPath,
        ]);

        return redirect()->route('goals.index')->with('success', 'Goals berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $goals = Goals::where('id_users', Auth::id())->findOrFail($id);
        return view('pages/goals/goals_edit', compact('goals'));
    }

    public function update(Request $request, $id)
    {
        $goal = Goals::where('id_users', Auth::id())->findOrFail($id);

        $validated = $request->validate([
            'judul_goals' => 'required|string|max:40',
            'jumlah_target' => 'required|numeric|min:1',
            'tgl_target' => 'required|date|after_or_equal:today',
            'gambar' => 'nullable|image|mimes:jpg,jpeg,png|max:2048',
        ], [
             'judul_goals' => 'Judul goals maksimal 40 karakter',
            'jumlah_target' => 'Jumlah target harus diisi angka positif',
            'tgl_target' => 'Tanggal target tidak boleh kurang dari tanggal sekarang',
            'gambar' => 'Gambar harus berupa file gambar (jpg, jpeg, png)',
            'gambar' => 'Ukuran gambar maksimal 2MB',
        ]);

        // Simpan gambar baru (hapus lama jika ada)
        if ($request->hasFile('gambar')) {
            if ($goal->gambar && Storage::disk('public')->exists($goal->gambar)) {
                Storage::disk('public')->delete($goal->gambar);
            }

            $validated['gambar'] = $request->file('gambar')->store('goals', 'public');
        }

        $goal->update($validated);

        return redirect()->route('goals.index')->with('success', 'Goals berhasil diperbarui.');
    }

    public function destroy($id)
    {
        $goal = Goals::where('id_users', Auth::id())->findOrFail($id);

        // Hapus gambar jika ada
        if ($goal->gambar && Storage::disk('public')->exists($goal->gambar)) {
            Storage::disk('public')->delete($goal->gambar);
        }

        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goals berhasil dihapus.');
    }

    public function setorCreate($id)
    {
        $goals = Goals::findOrFail($id);
        return view('pages/goals/setor_tambah', compact('goals'));
    }

    public function setorStore(Request $request, $id)
    {
        $request->validate([
            'jumlah_tabungan' => 'required|numeric|min:1',
        ], [
            'jumlah_tabungan' => 'Jumlah tabungan harus berupa angka positif',
            'jumlah_tabungan' => 'Setor Tabungan tidak boleh Rp 0',
        ]);

        $goals = Goals::findOrFail($id);

        Tabungan::create([
            'id_goals' => $goals->id,
            'jumlah_tabungan' => $request->jumlah_tabungan,
        ]);

        $goals->current_amount += $request->jumlah_tabungan;
        $goals->save();

        return redirect()->route('goals.index')->with('success', 'Setoran berhasil ditambahkan!');
    }
}
