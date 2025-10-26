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
    public function index()
    {
        $userId = Auth::id();

        $forums = ForumOrganisasi::with(['anggota' => function ($q) use ($userId) {
            $q->where('id_users', $userId);
        }])
            ->whereIn('id', function ($query) use ($userId) {
                $query->select('id_forum')
                    ->from('anggota_forum')
                    ->where('id_users', $userId);
            })
            ->orWhere('id_users', $userId) // untuk forum yang dia buat
            ->get();

        return view('pages.Forum.forum', compact('forums'));
    }

    public function indexAdd()
    {
        return view('pages.Forum.forum_add');
    }

    public function add(Request $request)
    {
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

        $gambarPath = null;
        if ($request->hasFile('gambar_forum')) {
            $gambarPath = $request->file('gambar_forum')->store('forum_images', 'public');
        }

        $validated['link_akses'] = Str::random(15);
        $validated['slug'] = Str::slug($validated['forum']);

        ForumOrganisasi::create([
            'id_users' => Auth::user()->id,
            'forum' => $request->forum,
            'deskripsi' => $request->deskripsi,
            'gambar_forum' => $gambarPath,
            'link_akses' => $validated['link_akses'],
        ]);

        AnggotaForum::create([
            'id_forum' => ForumOrganisasi::latest()->first()->id,
            'id_users' => Auth::user()->id,
            'role' => 'bendahara',
        ]);

        return redirect()->route('forum.index')->with('success', 'Forum berhasil ditambahkan!');
    }

    public function joinForm()
    {
        return view('pages.forum_join');
    }

    public function joinSubmit(Request $request)
    {
        $validated = $request->validate([
            'link_akses' => 'required|string|max:150'
        ], [
            'link_akses.required' => 'Kode forum wajib diisi.',
        ]);

        $link = $validated['link_akses'];

        // Kalau user memasukkan URL penuh, ambil kode terakhir dari URL
        if (str_contains($link, '/')) {
            $parts = explode('/', $link);
            $link = end($parts);
        }

        $forum = ForumOrganisasi::where('link_akses', $validated['link_akses'])->first();

        if (!$forum) {
            return back()->withErrors(['link_akses' => 'Kode forum tidak ditemukan atau tidak valid.']);
        }

        $userId = Auth::id();

        // Cek apakah user sudah bergabung
        $alreadyMember = DB::table('anggota_forum')
            ->where('id_forum', $forum->id)
            ->where('id_users', $userId)
            ->exists();

        if ($alreadyMember) {
            return redirect()->route('forum.index')->with('info', 'Kamu sudah tergabung di forum ini.');
        }

        // Tambahkan user ke forum
        DB::table('anggota_forum')->insert([
            'id_forum' => $forum->id,
            'id_users' => $userId,
            'role' => 'anggota',
        ]);

        return redirect()->route('forum.index')->with('success', "Berhasil bergabung ke forum: {$forum->forum}");
    }
}
