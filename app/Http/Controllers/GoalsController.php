<?php

namespace App\Http\Controllers;

use App\Models\Goals;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Storage;
use App\Models\Tabungan;

class GoalsController extends Controller
{
    /**
     * Menampilkan daftar goals milik user yang sedang login.
     */
    public function index()
    {
        // Ambil semua goals berdasarkan id user yang login
        $goals = Goals::where('id_users', Auth::id())->get();

        // Tambahkan properti status untuk setiap goal
        foreach ($goals as $goal) {
            $goal->status = $goal->current_amount >= $goal->jumlah_target
                ? 'Tercapai'
                : 'Belum Tercapai';
        }

        // Kirim data goals ke view
        return view('pages/goals/goals', compact('goals'));
    }

    /**
     * Menampilkan form untuk menambah goals baru.
     */
    public function create()
    {
        return view('pages/goals/goals_tambah');
    }

    /**
     * Menyimpan goals baru ke database.
     */
    public function store(Request $request)
    {
        // Validasi input dari form
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

        // Jika user mengunggah gambar, simpan ke storage/public/goals
        $gambarPath = $request->hasFile('gambar')
            ? $request->file('gambar')->store('goals', 'public')
            : null;

        // Simpan data goals ke database
        Goals::create([
            'id_users' => Auth::id(),
            'judul_goals' => $validated['judul_goals'],
            'jumlah_target' => $validated['jumlah_target'],
            'tgl_target' => $validated['tgl_target'],
            'current_amount' => 0, // Awal tabungan 0
            'gambar' => $gambarPath,
        ]);

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('goals.index')->with('success', 'Goals berhasil ditambahkan.');
    }

    /**
     * Menampilkan form untuk mengedit goals berdasarkan ID.
     */
    public function edit($id)
    {
        // Pastikan goals milik user yang sedang login
        $goals = Goals::where('id_users', Auth::id())->findOrFail($id);
        return view('pages/goals/goals_edit', compact('goals'));
    }

    /**
     * Memperbarui data goals yang ada di database.
     */
    public function update(Request $request, $id)
    {
        // Ambil goals berdasarkan ID dan user yang login
        $goal = Goals::where('id_users', Auth::id())->findOrFail($id);

        // Validasi data baru dari form edit
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

        // Jika user mengganti gambar, hapus yang lama dari storage
        if ($request->hasFile('gambar')) {
            if ($goal->gambar && Storage::disk('public')->exists($goal->gambar)) {
                Storage::disk('public')->delete($goal->gambar);
            }

            // Simpan gambar baru ke storage
            $validated['gambar'] = $request->file('gambar')->store('goals', 'public');
        }

        // Update data goals di database
        $goal->update($validated);

        // Kembali ke halaman index dengan pesan sukses
        return redirect()->route('goals.index')->with('success', 'Goals berhasil diperbarui.');
    }

    /**
     * Menghapus goals dari database (beserta gambar jika ada).
     */
    public function destroy($id)
    {
        // Ambil goals milik user
        $goal = Goals::where('id_users', Auth::id())->findOrFail($id);

        // Jika ada gambar di storage, hapus
        if ($goal->gambar && Storage::disk('public')->exists($goal->gambar)) {
            Storage::disk('public')->delete($goal->gambar);
        }

        // Hapus goals dari database
        $goal->delete();

        return redirect()->route('goals.index')->with('success', 'Goals berhasil dihapus.');
    }

    /**
     * Menampilkan form untuk menambah setoran tabungan ke goals tertentu.
     */
    public function setorCreate($id)
    {
        $goals = Goals::findOrFail($id);
        return view('pages/goals/setor_tambah', compact('goals'));
    }

    /**
     * Menyimpan data setoran tabungan baru untuk goals.
     */
    public function setorStore(Request $request, $id)
    {
        // Validasi nominal setoran
        $request->validate([
            'jumlah_tabungan' => 'required|numeric|min:1',
        ], [
            'jumlah_tabungan' => 'Jumlah tabungan harus berupa angka positif',
            'jumlah_tabungan' => 'Setor Tabungan tidak boleh Rp 0',
        ]);

        // Cari goals berdasarkan ID
        $goals = Goals::findOrFail($id);

        // Tambahkan data setoran ke tabel Tabungan
        Tabungan::create([
            'id_goals' => $goals->id,
            'jumlah_tabungan' => $request->jumlah_tabungan,
        ]);

        // Tambahkan jumlah setoran ke saldo (current_amount)
        $goals->current_amount += $request->jumlah_tabungan;
        $goals->save();

        // Kembali ke halaman goals dengan pesan sukses
        return redirect()->route('goals.index')->with('success', 'Setoran berhasil ditambahkan!');
    }
}

