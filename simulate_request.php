<?php
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\User;
use App\Http\Controllers\AppointmentController;
use Illuminate\Http\Request;

$u = User::find(8);
auth()->login($u);

$request = Request::create('/appointments/datatable', 'GET', [
    'draw' => 1,
    'start' => 0,
    'length' => 10,
    'my_appointments' => 1
]);

$controller = new AppointmentController();
$response = $controller->datatable($request);

echo "Response Status: " . $response->getStatusCode() . "\n";
echo "Response Content: " . json_encode(json_decode($response->getContent()), JSON_PRETTY_PRINT) . "\n";
