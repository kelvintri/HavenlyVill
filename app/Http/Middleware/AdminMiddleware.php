<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Middleware AdminMiddleware — membatasi akses hanya untuk admin.
 *
 * Menerapkan requirement (d): percabangan if-else.
 * Menerapkan requirement (e): penggunaan method.
 *
 * @package App\Http\Middleware
 */
class AdminMiddleware
{
    /**
     * Menangani request yang masuk.
     * Hanya user dengan role 'admin' yang boleh mengakses route yang dilindungi.
     *
     * @param Request $request
     * @param Closure $next
     * @return Response
     */
    public function handle(Request $request, Closure $next): Response
    {
        // Percabangan if-else (Req d)
        if (!auth()->check()) {
            return redirect()->route('login');
        }

        if (!auth()->user()->isAdmin()) {
            abort(403, 'Akses ditolak. Anda bukan admin.');
        }

        return $next($request);
    }
}
