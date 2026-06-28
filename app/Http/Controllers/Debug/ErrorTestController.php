<?php
namespace App\Http\Controllers\Debug;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\RateLimiter;
use Symfony\Component\HttpKernel\Exception\HttpException;
class ErrorTestController extends Controller
{
    public function unauthorized()
    {
        abort(401, 'Unauthorized – please log in');
    }
    public function paymentRequired()
    {
        abort(402, 'Payment Required – upgrade your plan');
    }
    public function forbidden()
    {
        abort(403, 'Forbidden – insufficient permissions');
    }
    public function notFound()
    {
        abort(404);
    }
    public function pageExpired()
    {
        abort(419, 'Page Expired – CSRF token mismatch');
    }
    public function tooManyRequests()
    {
        abort(429, 'Too Many Requests – slow down');
    }
    public function serverError()
    {
        throw new \Exception('Intentional server error for testing');
    }
    public function maintenance()
    {
        Artisan::call('down', ['--secret' => 'testing-secret-123']);
        abort(503, 'Service Unavailable – maintenance mode');
    }
    public function any($code = null)
    {
        $code = (int) ($code ?? 418); 
        if ($code === 503) {
            return $this->maintenance();
        }
        if ($code === 500) {
            return $this->serverError();
        }
        if (in_array($code, [401, 402, 403, 404, 419, 429])) {
            abort($code);
        }
        abort($code, "Testing HTTP {$code}");
    }
}
