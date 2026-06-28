<?php
namespace App\Console\Commands;
use Illuminate\Console\Command;
use Spatie\Sitemap\Sitemap;
use Spatie\Sitemap\Tags\Url;
use App\Models\Product;
use App\Models\Category;
use App\Models\Collection;
class GenerateSitemap extends Command
{
    protected $signature = 'sitemap:generate';
    protected $description = 'Generate the sitemap.xml for the customer-facing website.';
    public function handle()
    {
        $this->info('Generating sitemap...');
        $sitemap = Sitemap::create();
        $sitemap->add(
            Url::create(route('home'))
               ->setPriority(1.0)
               ->setChangeFrequency('daily')
        );
        $this->info('Adding products...');
        Product::where('is_visible', true)
            ->select('id', 'slug', 'updated_at')
            ->orderBy('updated_at', 'desc')
            ->each(function (Product $product) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('frontend.products.show', $product->slug))
                       ->setLastModificationDate($product->updated_at)
                       ->setPriority(0.9)
                       ->setChangeFrequency('weekly')
                );
            });
        $this->info('Adding categories...');
        Category::where('is_active', true)
            ->select('id', 'slug', 'updated_at')
            ->each(function (Category $category) use ($sitemap) {
                $sitemap->add(
                    Url::create(route('frontend.products.index', ['category' => $category->slug]))
                       ->setLastModificationDate($category->updated_at)
                       ->setPriority(0.8)
                       ->setChangeFrequency('weekly')
                );
            });
        $this->info('Adding collections...');
        if (class_exists(Collection::class)) {
            Collection::where('is_active', true)
                ->select('id', 'slug', 'updated_at')
                ->each(function (Collection $collection) use ($sitemap) {
                    $sitemap->add(
                        Url::create(route('frontend.products.index', ['collection' => $collection->slug]))
                           ->setLastModificationDate($collection->updated_at)
                           ->setPriority(0.7)
                           ->setChangeFrequency('weekly')
                    );
                });
        }
        $staticPages = [
            'frontend.products.index' => 0.8,
        ];
        foreach ($staticPages as $routeName => $priority) {
            try {
                $sitemap->add(
                    Url::create(route($routeName))
                       ->setPriority($priority)
                       ->setChangeFrequency('weekly')
                );
            } catch (\Exception $e) {
            }
        }
        $sitemap->writeToFile(public_path('sitemap.xml'));
        $this->info('Sitemap generated successfully at public/sitemap.xml');
    }
}
