<?php

namespace App\Http\Controllers;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Str;

class ForumController extends Controller
{
    /**
     * Tampilkan daftar forum yang dimiliki atau diikuti oleh user saat ini.
     * 
     * Logika:
     * - Ambil ID user yang sedang login.
     * - Ambil semua forum di mana user terdaftar sebagai anggota (melalui tabel anggota_forum).
     * - Atau forum yang dibuat oleh user itu sendiri.
     */
    public function index()
    {
        $userId = Auth::id();

        // Query forum yang user ikuti atau buat sendiri
        $forums = ForumOrganisasi::with(['anggota' => function ($q) use ($userId) {
            $q->where('id_users', $userId); // filter relasi anggota yang sesuai user
        }])
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('id_forum')
                    ->from('anggota_forum')
                    ->where('id_users', $userId);
            })
            ->orWhere('id_users', $userId) // forum yang dibuat oleh user sendiri
            ->get();

        return view('pages.Forum.forum', compact('forums'));
    }

    /**
     * Menampilkan halaman form untuk menambah forum baru.
     */
    public function indexAdd()
    {
        return view('pages.Forum.forum_add');
    }

    /**
     * Proses penambahan forum baru oleh user.
     * 
     * Langkah:
     * 1. Validasi input form.
     * 2. Simpan gambar jika ada.
     * 3. Generate link akses unik dan slug dari nama forum.
     * 4. Simpan forum ke database.
     * 5. Tambahkan pembuat forum sebagai anggota dengan role 'bendahara'.
     */
    public function add(Request $request)
    {
        // Validasi input
        $validated = $request->validate([
            'forum' => 'required|string|max:255',
            'deskripsi' => 'required|string',
            'gambar_forum' => 'nullable|image|max:2048',
            'link_akses' => 'nullable|url',
        ], [
            'forum.required' => 'Nama forum wajib diisi.',
            'forum.max' => 'Nama forum maksimal 100 karakter.',
            'deskripsi.string' => 'Deskripsi forum harus berupa teks.',
            'gambar_forum.image' => 'File yang diunggah harus berupa gambar.',
            'gambar_forum.mimes' => 'Gambar harus berformat jpg, jpeg, atau png.',
            'gambar_forum.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        // Upload gambar jika ada
        $gambarPath = null;
        if ($request->hasFile('gambar_forum')) {
            // Simpan gambar ke storage/public/forum_images
            $gambarPath = $request->file('gambar_forum')->store('forum_images', 'public');
        }

        // Buat link acak dan slug dari nama forum
        $validated['link_akses'] = Str::random(15);
        $validated['slug'] = Str::slug($validated['forum']);

        // Simpan forum ke database
        ForumOrganisasi::create([
            'id_users' => Auth::user()->id,
            'forum' => $request->forum,
            'deskripsi' => $request->deskripsi,
            'gambar_forum' => $gambarPath,
            'link_akses' => $validated['link_akses'],
        ]);

        // Ambil forum terakhir (yang baru saja dibuat) dan tambahkan pembuatnya ke tabel anggota_forum
        AnggotaForum::create([
            'id_forum' => ForumOrganisasi::latest()->first()->id,
            'id_users' => Auth::user()->id,
            'role' => 'bendahara', // role default untuk pembuat forum
        ]);

        return redirect()->route('forum.index')->with('success', 'Forum berhasil ditambahkan!');
    }

    /**
     * Proses user bergabung ke forum menggunakan kode/link akses.
     * 
     * Langkah:
     * 1. Validasi input link akses.
     * 2. Ekstrak kode dari URL (jika user menempelkan link penuh).
     * 3. Cek apakah kode forum valid.
     * 4. Jika valid, tambahkan user ke tabel anggota_forum (kecuali sudah tergabung).
     */
    public function joinSubmit(Request $request)
    {
        // Validasi input kode forum
        $validated = $request->validate([
            'link_akses' => 'required|string|max:150'
        ], [
            'link_akses.required' => 'Kode forum wajib diisi.',
        ]);

        $link = $validated['link_akses'];

        // Jika user menempelkan URL penuh, ambil kode terakhir (setelah '/')
        if (str_contains($link, '/')) {
            $parts = explode('/', $link);
            $link = end($parts);
        }

        // Cari forum berdasarkan link akses
        $forum = ForumOrganisasi::where('link_akses', $validated['link_akses'])->first();

        if (!$forum) {
            // Jika tidak ditemukan, tampilkan pesan error
            return back()->withErrors(['link_akses' => 'Kode forum tidak ditemukan atau tidak valid.']);
        }

        $userId = Auth::id();

        // Cek apakah user sudah menjadi anggota forum ini
        $alreadyMember = DB::table('anggota_forum')
            ->where('id_forum', $forum->id)
            ->where('id_users', $userId)
            ->exists();

        if ($alreadyMember) {
            // Jika sudah tergabung, tampilkan notifikasi info
            return redirect()->route('forum.index')->with('info', 'Kamu sudah tergabung di forum ini.');
        }

        // Tambahkan user sebagai anggota baru forum
        DB::table('anggota_forum')->insert([
            'id_forum' => $forum->id,
            'id_users' => $userId,
            'role' => 'anggota', // role default saat bergabung
        ]);

        return redirect()->route('forum.index')->with('success', "Berhasil bergabung ke forum: {$forum->forum}");
    }
}
