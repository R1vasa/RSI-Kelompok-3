<?php

namespace App\Http\Middleware;

use App\Models\AnggotaForum;
use App\Models\ForumOrganisasi;
use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Symfony\Component\HttpFoundation\Response;

class hakAkses
{
    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        $slug = $request->route('slug');
        $forum = ForumOrganisasi::where('slug', $slug)->first();

        if (!$forum) {
            abort(404, 'Forum tidak ditemukan.');
        }

        $isMember = AnggotaForum::where('id_users', Auth::id())
            ->where('id_forum', $forum->id)
            ->exists();

        if (!$isMember) {
            return redirect()->route('forum.index', ['slug' => $slug])
                ->with('error', 'Anda harus bergabung ke forum terlebih dahulu.');
        }

        $request->attributes->add(['forum' => $forum]);

        return $next($request);
    }
}
