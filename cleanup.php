<?php
require 'vendor/autoload.php';
$app = require_once 'bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

Schema::dropIfExists('shipment_tracking_events');
Schema::dropIfExists('shipments');
Schema::dropIfExists('shipping_rates');
Schema::dropIfExists('pickup_locations');
Schema::dropIfExists('shipping_zones');
Schema::dropIfExists('couriers');

DB::table('migrations')->where('migration', 'like', '%couriers%')->delete();
DB::table('migrations')->where('migration', 'like', '%shipping_zones%')->delete();
DB::table('migrations')->where('migration', 'like', '%shipping_rates%')->delete();
DB::table('migrations')->where('migration', 'like', '%pickup_locations%')->delete();
DB::table('migrations')->where('migration', 'like', '%shipments%')->delete();
DB::table('migrations')->where('migration', 'like', '%shipment_tracking_events%')->delete();

echo "Cleanup complete.";
