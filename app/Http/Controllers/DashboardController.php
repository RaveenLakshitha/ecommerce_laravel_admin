<?php
namespace App\Http\Controllers;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;
class DashboardController extends Controller
{
    public function index()
    {
        $user = Auth::user();
        $today = Carbon::today();
        $data = [
            'user' => $user,
            'userName' => $user->name,
            'currentDate' => now()->format('l, d F Y'),
            'role' => $user->getRoleNames()->first() ?? 'user',
        ];
        return view('dashboard.default', $data);
    }
}
