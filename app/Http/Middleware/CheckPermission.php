<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckPermission
{
    public function handle(Request $request, Closure $next, string $permissionSlug): Response
    {
        $user = $request->user();

        if (! $user || ! $user->hasPermission($permissionSlug)) {
            abort(403, 'Anda tidak memiliki izin untuk melakukan aksi ini.');
        }

        return $next($request);
    }
}