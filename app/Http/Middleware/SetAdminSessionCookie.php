<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;

/**
 * Swap the session cookie name for admin-domain requests.
 *
 * This runs BEFORE the session is started so Laravel picks up
 * the correct cookie for each site:
 *   shop.karbnzol.com  → uses  'callofDoctor-session'  (default)
 *   admin.karbnzol.com → uses  'admin_session'
 *
 * Locally:
 *   localhost/test/CMS/public          → default cookie
 *   localhost/test/CMS/public/admin/*  → 'admin_session' cookie
 */
class SetAdminSessionCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        // Detect admin context — works both on production subdomains and locally.
        $isAdmin = $request->is('admin*') || $request->getHost() === 'admin.karbnzol.com';

        if ($isAdmin) {
            // Override the cookie name Laravel will use for this request.
            config(['session.cookie' => 'admin_session']);
        }

        return $next($request);
    }
}
