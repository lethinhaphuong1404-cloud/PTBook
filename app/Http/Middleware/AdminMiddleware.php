<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class AdminMiddleware
{
 public function handle(Request $request, Closure $next): Response
{
    if (!\Illuminate\Support\Facades\Auth::check()) {
        abort(403, 'Bạn chưa đăng nhập.');
    }

    /** @var \App\Models\User|null $user */
    $user = \Illuminate\Support\Facades\Auth::user();

    if (!$user || !$user->isAdmin()) {
        abort(403, 'Bạn không có quyền truy cập.');
    }

    return $next($request);
}
}
