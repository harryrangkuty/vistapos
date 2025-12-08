<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

class CheckUserPermissions
{
    protected $except_urls = [
        'dashboard',
        'logout',
        'profile',
        'profile/write',
        'test',
    ];

    /**
     * Handle an incoming request.
     *
     * @param  \Closure(\Illuminate\Http\Request): (\Symfony\Component\HttpFoundation\Response)  $next
     */
    public function handle(Request $request, Closure $next): Response
    {
        if (!check_user_permissions($request->user(), $request->path(), $this->except_urls, $request)) {
            if ($request->ajax())
                return response()->json(__('auth.permission_error'), 403);

            abort(403, "Forbidden access!");
        }

        return $next($request);
    }

    public static function getExceptUrls()
    {
        $obj = new self();
        return $obj->except_urls;
    }
}
