<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\ProductImage;
use App\Models\Category;
use App\Models\Collection;
use App\Models\Brand;
use Illuminate\Support\Facades\Storage;

class MigrateMediaCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'media:migrate-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Migrate existing product images to Spatie Media Library';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $this->info('Starting media migration...');

        $productImages = ProductImage::whereNotNull('file_path')->get();
        $count = 0;

        foreach ($productImages as $image) {
            // Check if it already has media
            if ($image->hasMedia('images')) {
                continue;
            }

            $path = Storage::disk('public')->path($image->file_path);

            if (file_exists($path)) {
                $this->info("Migrating image ID: {$image->id} - {$image->file_path}");
                try {
                    $image->addMedia($path)
                          ->preservingOriginal()
                          ->toMediaCollection('images');
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate ID {$image->id}: " . $e->getMessage());
                }
            } else {
                $this->warn("File not found for ID {$image->id}: {$path}");
            }
        }

        $categories = Category::whereNotNull('image')->get();
        foreach ($categories as $cat) {
            if ($cat->hasMedia('images')) continue;
            
            $path = Storage::disk('public')->path($cat->image);
            if (file_exists($path)) {
                $this->info("Migrating category ID: {$cat->id} - {$cat->image}");
                try {
                    $cat->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate category ID {$cat->id}: " . $e->getMessage());
                }
            } else {
                $this->warn("File not found for category ID {$cat->id}: {$path}");
            }
        }

        $collections = Collection::whereNotNull('banner_url')->get();
        foreach ($collections as $col) {
            if ($col->hasMedia('images')) continue;

            $path = Storage::disk('public')->path($col->banner_url);
            if (file_exists($path)) {
                $this->info("Migrating collection ID: {$col->id} - {$col->banner_url}");
                try {
                    $col->addMedia($path)
                        ->preservingOriginal()
                        ->toMediaCollection('images');
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate collection ID {$col->id}: " . $e->getMessage());
                }
            } else {
                $this->warn("File not found for collection ID {$col->id}: {$path}");
            }
        }

        $brands = Brand::whereNotNull('logo_path')->get();
        foreach ($brands as $brand) {
            if ($brand->hasMedia('images')) continue;

            $path = Storage::disk('public')->path($brand->logo_path);
            if (file_exists($path)) {
                $this->info("Migrating brand ID: {$brand->id} - {$brand->logo_path}");
                try {
                    $brand->addMedia($path)
                          ->preservingOriginal()
                          ->toMediaCollection('images');
                    $count++;
                } catch (\Exception $e) {
                    $this->error("Failed to migrate brand ID {$brand->id}: " . $e->getMessage());
                }
            } else {
                $this->warn("File not found for brand ID {$brand->id}: {$path}");
            }
        }

        $this->info("Migration completed. {$count} images migrated successfully.");
    }
}
