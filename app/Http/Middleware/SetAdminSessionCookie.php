<?php
namespace App\Http\Middleware;
use Closure;
use Illuminate\Http\Request;
use Symfony\Component\HttpFoundation\Response;
class SetAdminSessionCookie
{
    public function handle(Request $request, Closure $next): Response
    {
        $isAdmin = $request->is('admin*') || $request->getHost() === 'admin.karbnzol.cloud' || $request->routeIs('admin.*') || $request->segment(1) === 'admin';
        if ($isAdmin) {
            config(['session.cookie' => 'admin_session']);
        }
        return $next($request);
    }
}
