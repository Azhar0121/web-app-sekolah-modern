<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string ...$permissions): Response
    {
        $user = $request->user();

        if (! $user) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        foreach ($permissions as $perm) {
            $slugs = explode(',', $perm);
            foreach ($slugs as $slug) {
                if ($user->hasPermission(trim($slug))) {
                    return $next($request);
                }
            }
        }

        abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
    }
}