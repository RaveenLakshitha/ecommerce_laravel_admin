<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Cache;

class StorefrontController extends Controller
{
    public function __construct()
    {
        $this->middleware('permission:settings.index', ['only' => ['index']]);
        $this->middleware('permission:settings.edit', ['only' => ['update']]);
    }

    public function index()
    {
        $setting = Setting::getAll();
        return view('admin.storefront.index', compact('setting'));
    }

    public function update(Request $request)
    {
        $setting = Setting::firstOrCreate([]);

        $validated = $request->validate([
            'phone' => 'nullable|string|max:20',
            'email' => 'nullable|email|max:255',
            'storefront_offer_text' => 'nullable|string|max:50',
            'storefront_offer_link' => 'nullable|string|max:255',
            'storefront_about_us' => 'nullable|string|max:250',
            
            'banners' => 'nullable|array',
            'banners.*.tag' => 'nullable|string|max:25',
            'banners.*.title' => 'nullable|string|max:25',
            'banners.*.subtitle' => 'nullable|string|max:120',
            'banners.*.link' => 'nullable|string|max:255',
            'banners.*.image' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:4096',
        ]);

        $data = $request->only([
            'phone',
            'email',
            'storefront_about_us',
            'storefront_offer_text',
            'storefront_offer_link'
        ]);

        $banners = is_array($setting->storefront_banners) ? $setting->storefront_banners : [];
        $newBanners = [];

        if ($request->has('banners')) {
            foreach ($request->banners as $index => $bannerData) {
                // Determine if there's an existing banner to keep its image
                $existingImage = $banners[$index]['image'] ?? null;
                $imagePath = $existingImage;

                // Check if a new image was uploaded
                if (isset($bannerData['image']) && $request->file("banners.{$index}.image")) {
                    $file = $request->file("banners.{$index}.image");
                    $imagePath = $file->store('banners', 'public');

                    // If we have an existing image and we're replacing it, we would normally delete the old one.
                    // But array restructuring might change indexes, so we just let it be or delete if we want.
                } elseif (isset($bannerData['remove_image']) && $bannerData['remove_image'] == '1') {
                    $imagePath = null;
                }

                // If all fields are empty and no image, maybe skip it
                if (empty($bannerData['title']) && empty($bannerData['subtitle']) && empty($bannerData['link']) && empty($imagePath)) {
                    continue;
                }

                $newBanners[] = [
                    'title' => $bannerData['title'] ?? '',
                    'subtitle' => $bannerData['subtitle'] ?? '',
                    'link' => $bannerData['link'] ?? '',
                    'image' => $imagePath,
                ];
            }
        }

        $data['storefront_banners'] = $newBanners;

        $setting->update($data);

        Cache::forget('settings');

        return redirect()->route('admin.storefront.index')->with('success', 'Storefront settings updated successfully.');
    }
}
