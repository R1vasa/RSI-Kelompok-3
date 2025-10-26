<?php

namespace App\Http\Middleware;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class isBendahara
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug'); // ambil slug dari URL
        $forum = ForumOrganisasi::where('slug', $slug)->first();

        if (!$forum) {
            return redirect()->back()->with('error', 'Forum tidak ditemukan.');
        }

        // cek apakah user terdaftar sebagai bendahara forum tersebut
        $isBendahara = AnggotaForum::where('id_forum', $forum->id)
            ->where('id_users', Auth::id())
            ->where('role', 'bendahara')
            ->exists();

        if (!$isBendahara) {
            return redirect()->back()->with('error', 'Anda tidak memiliki akses sebagai bendahara.');
        }

        return $next($request);
    }
}
