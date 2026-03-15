<?php
\ = <<<'PHP'
<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Storage;
use App\Models\Setting;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     */
    public function register(): void
    {
        //
    }

    /**
     * Bootstrap any application services.
     */
    public function boot(): void
    {
        if (!app()->runningInConsole() && !app()->runningUnitTests()) {
            try {
                \ = cache('settings')
                    ?? cache()->remember('settings', now()->addHour(), fn() => Setting::first() ?? (object)[]);
            } catch (\Throwable \) {
                \ = (object)[];
            }
        } else {
            \ = (object)[];
        }

        View::share([
            'clinic_name'   => \->clinic_name ?? config('app.name'),
            'clinic_logo'   => \->logo_path
                ? Storage::url(\->logo_path)
                : null,
            'primary_color' => \->primary_color ?? '#1e40af',
            'currency_code' => \->currency      ?? 'USD',
        ]);
    }
}
PHP;
file_put_contents('app/Providers/AppServiceProvider.php', \);
echo 'Done';

