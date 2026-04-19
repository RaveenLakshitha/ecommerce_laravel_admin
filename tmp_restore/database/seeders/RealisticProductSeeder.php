<?php

namespace Database\Seeders;

use App\Models\Attribute;
use App\Models\AttributeValue;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\Variant;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class RealisticProductSeeder extends Seeder
{
    public function run(): void
    {
        // ── Load all attributes & values ─────
        $sizeAttr = Attribute::where('slug', 'size')->firstOrFail();
        $colorAttr = Attribute::where('slug', 'color')->firstOrFail();
        $fitAttr = Attribute::where('slug', 'fit')->firstOrFail();
        $materialAttr = Attribute::where('slug', 'material')->firstOrFail();
        $patternAttr = Attribute::where('slug', 'pattern')->firstOrFail();
        $widthAttr = Attribute::where('slug', 'width')->firstOrFail();
        $heelAttr = Attribute::where('slug', 'heel-height')->firstOrFail();
        $toeAttr = Attribute::where('slug', 'toe-style')->firstOrFail();
        $closureAttr = Attribute::where('slug', 'closure-type')->firstOrFail();
        $metalAttr = Attribute::where('slug', 'metal-type')->firstOrFail();
        $gemAttr = Attribute::where('slug', 'gemstone')->firstOrFail();
        $lengthAttr = Attribute::where('slug', 'length')->firstOrFail();
        $jewelrySizeAttr = Attribute::where('slug', 'jewelry-size')->firstOrFail();
        $caseSizeAttr = Attribute::where('slug', 'case-size')->firstOrFail();
        $bandMaterialAttr = Attribute::where('slug', 'band-material')->firstOrFail();
        $activityAttr = Attribute::where('slug', 'activity-type')->firstOrFail();
        $compressionAttr = Attribute::where('slug', 'compression-level')->firstOrFail();

        $sizes = AttributeValue::where('attribute_id', $sizeAttr->id)->pluck('id', 'slug')->toArray();
        $colors = AttributeValue::where('attribute_id', $colorAttr->id)->pluck('id', 'slug')->toArray();
        $fits = AttributeValue::where('attribute_id', $fitAttr->id)->pluck('id', 'slug')->toArray();
        $materials = AttributeValue::where('attribute_id', $materialAttr->id)->pluck('id', 'slug')->toArray();
        $patterns = AttributeValue::where('attribute_id', $patternAttr->id)->pluck('id', 'slug')->toArray();
        $widths = AttributeValue::where('attribute_id', $widthAttr->id)->pluck('id', 'slug')->toArray();
        $heels = AttributeValue::where('attribute_id', $heelAttr->id)->pluck('id', 'slug')->toArray();
        $toes = AttributeValue::where('attribute_id', $toeAttr->id)->pluck('id', 'slug')->toArray();
        $closures = AttributeValue::where('attribute_id', $closureAttr->id)->pluck('id', 'slug')->toArray();
        $metals = AttributeValue::where('attribute_id', $metalAttr->id)->pluck('id', 'slug')->toArray();
        $gems = AttributeValue::where('attribute_id', $gemAttr->id)->pluck('id', 'slug')->toArray();
        $lengths = AttributeValue::where('attribute_id', $lengthAttr->id)->pluck('id', 'slug')->toArray();
        $jewelrySizes = AttributeValue::where('attribute_id', $jewelrySizeAttr->id)->pluck('id', 'slug')->toArray();
        $caseSizes = AttributeValue::where('attribute_id', $caseSizeAttr->id)->pluck('id', 'slug')->toArray();
        $bandMaterials = AttributeValue::where('attribute_id', $bandMaterialAttr->id)->pluck('id', 'slug')->toArray();
        $activities = AttributeValue::where('attribute_id', $activityAttr->id)->pluck('id', 'slug')->toArray();
        $compressions = AttributeValue::where('attribute_id', $compressionAttr->id)->pluck('id', 'slug')->toArray();

        // ── Load brands ─────────────────────────────
        $nike = Brand::where('slug', 'nike')->first();
        $adidas = Brand::where('slug', 'adidas')->first();
        $puma = Brand::where('slug', 'puma')->first();
        $lululemon = Brand::where('slug', 'lululemon')->first();
        $converse = Brand::where('slug', 'converse')->first();
        $pandora = Brand::where('slug', 'pandora')->first();
        $rayban = Brand::where('slug', 'ray-ban')->first();
        $zara = Brand::where('slug', 'zara')->first();
        $local = Brand::where('slug', 'local-threads')->first();
        
        // Extra Brands for 12 new products
        $levis = Brand::where('slug', 'levis')->first();
        $hm = Brand::where('slug', 'hm')->first();
        $uniqlo = Brand::where('slug', 'uniqlo')->first();
        $tommy = Brand::where('slug', 'tommy-hilfiger')->first();
        $ck = Brand::where('slug', 'calvin-klein')->first();
        $nb = Brand::where('slug', 'new-balance')->first();
        $drmartens = Brand::where('slug', 'dr-martens')->first();
        $gymshark = Brand::where('slug', 'gymshark')->first();
        $ua = Brand::where('slug', 'under-armour')->first();
        $mk = Brand::where('slug', 'michael-kors')->first();
        $fossil = Brand::where('slug', 'fossil')->first();

        // ── Fetch categories ─────
        $mensHoodies = Category::where('slug', Str::slug("Men's Clothing Hoodies & Sweatshirts"))->first();
        $mensJeans = Category::where('slug', Str::slug("Men's Clothing Jeans & Denim"))->first();
        $mensShirts = Category::where('slug', Str::slug("Men's Clothing Shirts & T-shirts"))->first();
        $mensPolos = Category::where('slug', Str::slug("Men's Clothing Polo Shirts"))->first();
        $mensUnderwear = Category::where('slug', Str::slug("Men's Clothing Underwear & Socks"))->first();
        $womensDresses = Category::where('slug', Str::slug("Women's Clothing Dresses"))->first();
        $womensCoats = Category::where('slug', Str::slug("Women's Clothing Coats & Jackets"))->first();
        $womensLeggings = Category::where('slug', Str::slug("Women’s Activewear Leggings"))->first();
        $athleticShoes = Category::where('slug', Str::slug("Women’s Shoes Athletic Shoes & Sneakers"))->first();
        $mensAthleticShoes = Category::where('slug', Str::slug("Men’s Shoes Athletic Shoes & Sneakers"))->first();
        $womensCasualShoes = Category::where('slug', Str::slug("Women’s Shoes Casual Shoes"))->first();
        $womensBoots = Category::where('slug', Str::slug("Women’s Shoes Boots"))->first();
        $mensActiveTees = Category::where('slug', Str::slug("Men’s Activewear T-Shirts & Tanks"))->first();
        $necklacesCat = Category::where('slug', Str::slug("Jewelry Necklaces"))->first();
        $aviatorCat = Category::where('slug', Str::slug("Sunglasses Aviator"))->first();
        $bagsTotes = Category::where('slug', Str::slug("Bags & Wallets Tote Bags"))->first();
        $watchesMen = Category::where('slug', Str::slug("Watches Men’s Watches"))->first();


        // ── Product 1: Nike Sport T-Shirt (Men) ─────────────────────────────────────
        $tshirt = Product::updateOrCreate(
            ['slug' => Str::slug('Nike Sportswear Club T-Shirt')],
            [
                'name' => 'Nike Sportswear Club T-Shirt',
                'description' => 'Classic cotton crew-neck tee with embroidered Swoosh. Soft, breathable and perfect for daily wear.',
                'short_description' => 'Iconic Nike cotton t-shirt',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $tshirt->brand_id = $nike->id ?? null; $tshirt->save();
        if ($mensHoodies) $tshirt->categories()->syncWithoutDetaching([$mensHoodies->id]); 

        $this->createVariants($tshirt, [
            ['sku' => 'NK-TS-BLK-S', 'price' => 39.99, 'sale_price' => 29.99, 'stock' => 85, 'sizes' => ['s'], 'colors' => ['black'], 'fit' => 'regular', 'material' => 'cotton'],
            ['sku' => 'NK-TS-WHT-M', 'price' => 39.99, 'stock' => 120, 'sizes' => ['m'], 'colors' => ['white'], 'fit' => 'regular', 'material' => 'cotton'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 2: Adidas Originals Jeans (Men) ─────────────────────────────────
        $jeans = Product::updateOrCreate(
            ['slug' => Str::slug('Adidas Originals Adicolor Trefoil Jeans')],
            [
                'name' => 'Adidas Originals Adicolor Trefoil Jeans',
                'description' => 'Slim-fit stretch denim with iconic Trefoil embroidery. Comfortable all-day wear.',
                'short_description' => 'Classic Adidas slim jeans',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $jeans->brand_id = $adidas->id ?? null; $jeans->save();
        if ($mensJeans) $jeans->categories()->syncWithoutDetaching([$mensJeans->id]);

        $this->createVariants($jeans, [
            ['sku' => 'AD-JNS-BLK-30', 'price' => 89.99, 'sale_price' => 69.99, 'stock' => 28, 'sizes' => ['30'], 'colors' => ['black'], 'fit' => 'slim', 'material' => 'denim'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 3: Lululemon Align Leggings ──────────────────
        $leggings = Product::updateOrCreate(
            ['slug' => Str::slug('Lululemon Align High-Rise Leggings')],
            [
                'name' => 'Lululemon Align High-Rise Leggings',
                'description' => 'Buttery-soft, sweat-wicking leggings with compressive feel. Perfect for yoga and everyday wear.',
                'short_description' => 'Best-selling Lululemon yoga leggings',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $leggings->brand_id = $lululemon->id ?? null; $leggings->save();
        if ($womensLeggings) $leggings->categories()->syncWithoutDetaching([$womensLeggings->id]);

        $this->createVariants($leggings, [
            ['sku' => 'LL-LG-BLK-S', 'price' => 98.00, 'stock' => 65, 'sizes' => ['s'], 'colors' => ['black'], 'fit' => 'athletic', 'activity' => 'yoga', 'compression' => 'medium'],
        ], $sizes, $colors, $fits, null, $activities, $compressions);

        // ── Product 4: Converse Chuck Taylor Sneakers ─────────────────
        $sneakers = Product::updateOrCreate(
            ['slug' => Str::slug('Converse Chuck Taylor All Star Low Top')],
            [
                'name' => 'Converse Chuck Taylor All Star Low Top',
                'description' => 'Iconic canvas sneakers with rubber toe cap and vulcanized sole. Timeless street style.',
                'short_description' => 'Classic Converse sneakers',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $sneakers->brand_id = $converse->id ?? null; $sneakers->save();
        if ($athleticShoes) $sneakers->categories()->syncWithoutDetaching([$athleticShoes->id]);

        $this->createVariants($sneakers, [
            ['sku' => 'CV-SNK-WHT-7', 'price' => 65.00, 'stock' => 110, 'sizes' => ['7'], 'colors' => ['white'], 'width' => 'medium', 'heel' => 'flat', 'toe' => 'round-toe', 'closure' => 'lace-up'],
        ], $sizes, $colors, null, null, null, null, $widths, $heels, $toes, $closures);

        // ── Product 5: Pandora Sparkling Necklace ─────────────────────
        $necklace = Product::updateOrCreate(
            ['slug' => Str::slug('Pandora Sparkling Tennis Necklace')],
            [
                'name' => 'Pandora Sparkling Tennis Necklace',
                'description' => 'Sterling silver tennis necklace with cubic zirconia stones. Elegant everyday jewelry.',
                'short_description' => 'Pandora sparkling necklace',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $necklace->brand_id = $pandora->id ?? null; $necklace->save();
        if ($necklacesCat) $necklace->categories()->syncWithoutDetaching([$necklacesCat->id]);

        $this->createVariants($necklace, [
            ['sku' => 'PD-NK-SIL-16', 'price' => 149.00, 'stock' => 35, 'sizes' => ['16'], 'colors' => ['white'], 'metal' => 'silver', 'gem' => 'cubic-zirconia', 'length' => 'princess'],
        ], $sizes, $colors, null, null, null, null, null, null, null, null, $metals, $gems, $lengths);

        // ── Product 6: Ray-Ban Aviator Sunglasses ───────────────────────────────────
        $sunglasses = Product::updateOrCreate(
            ['slug' => Str::slug('Ray-Ban Classic Aviator Sunglasses')],
            [
                'name' => 'Ray-Ban Classic Aviator Sunglasses',
                'description' => 'Iconic gold-frame aviators with green polarized lenses. Timeless protection and style.',
                'short_description' => 'Legendary Ray-Ban aviators',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $sunglasses->brand_id = $rayban->id ?? null; $sunglasses->save();
        if ($aviatorCat) $sunglasses->categories()->syncWithoutDetaching([$aviatorCat->id]);

        $this->createVariants($sunglasses, [
            ['sku' => 'RB-AV-GOLD', 'price' => 179.00, 'sale_price' => 149.00, 'stock' => 62, 'sizes' => ['m'], 'colors' => ['brown'], 'material' => 'metal'],
        ], $sizes, $colors, null, $materials);

        // ── Product 7: Zara Floral Summer Dress ─────────────────────────────
        $dress = Product::updateOrCreate(
            ['slug' => Str::slug('Zara Floral Print Midi Dress')],
            [
                'name' => 'Zara Floral Print Midi Dress',
                'description' => 'Lightweight midi dress with vibrant floral print and tie waist. Perfect for summer occasions.',
                'short_description' => 'Zara floral midi dress',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $dress->brand_id = $zara->id ?? null; $dress->save();
        if ($womensDresses) $dress->categories()->syncWithoutDetaching([$womensDresses->id]);

        $this->createVariants($dress, [
            ['sku' => 'ZA-DR-FLR-S', 'price' => 49.99, 'stock' => 29, 'sizes' => ['s'], 'colors' => ['pink'], 'fit' => 'regular', 'pattern' => 'floral', 'material' => 'viscose'],
        ], $sizes, $colors, $fits, $materials, null, null, null, null, null, null, null, null, null, $patterns);

        // ── Product 8: Local Threads Oversized Hoodie ─────────────────────────
        $hoodie = Product::updateOrCreate(
            ['slug' => Str::slug('Local Threads Heavyweight Oversized Hoodie')],
            [
                'name' => 'Local Threads Heavyweight Oversized Hoodie',
                'description' => 'Premium Sri Lankan streetwear hoodie with dropped shoulders and kangaroo pocket.',
                'short_description' => 'Local Threads oversized hoodie',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $hoodie->brand_id = $local->id ?? null; $hoodie->save();
        if ($mensHoodies) $hoodie->categories()->syncWithoutDetaching([$mensHoodies->id]);

        $this->createVariants($hoodie, [
            ['sku' => 'LT-HD-BLK-M', 'price' => 59.99, 'sale_price' => 49.99, 'stock' => 55, 'sizes' => ['m'], 'colors' => ['black'], 'fit' => 'oversized', 'material' => 'fleece'],
        ], $sizes, $colors, $fits, $materials);

        // ============================= 12 NEW PRODUCTS =============================

        // ── Product 9: Puma Running Shoes Men ──────────────────────────────────────────
        $pumaP = Product::updateOrCreate(
            ['slug' => Str::slug('Puma Velocity Nitro 2 Running Shoes')],
            [
                'name' => 'Puma Velocity Nitro 2 Running Shoes',
                'description' => 'High-performance running shoes with nitrogen-injected foam for superior responsiveness.',
                'short_description' => 'Responsive Puma runner',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $pumaP->brand_id = $puma->id ?? null; $pumaP->save();
        if ($mensAthleticShoes) $pumaP->categories()->syncWithoutDetaching([$mensAthleticShoes->id]);

        $this->createVariants($pumaP, [
            ['sku' => 'PM-RN-ORG-10', 'price' => 120.00, 'stock' => 40, 'sizes' => ['10'], 'colors' => ['orange'], 'width' => 'medium', 'toe' => 'round-toe', 'closure' => 'lace-up'],
        ], $sizes, $colors, null, null, null, null, $widths, null, $toes, $closures);

        // ── Product 10: Levis 501 Original Fit Jeans ────────────────────────────────────
        $levisP = Product::updateOrCreate(
            ['slug' => Str::slug('Levis 501 Original Fit Jeans')],
            [
                'name' => 'Levis 501 Original Fit Jeans',
                'description' => 'The original blue jean since 1873. Straight fit with iconic styling and robust denim.',
                'short_description' => 'Iconic Levi\'s 501 Jeans',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $levisP->brand_id = $levis->id ?? null; $levisP->save();
        if ($mensJeans) $levisP->categories()->syncWithoutDetaching([$mensJeans->id]);

        $this->createVariants($levisP, [
            ['sku' => 'LV-501-BLU-32', 'price' => 79.50, 'stock' => 150, 'sizes' => ['32'], 'colors' => ['blue'], 'fit' => 'straight', 'material' => 'denim'],
            ['sku' => 'LV-501-BLK-34', 'price' => 79.50, 'sale_price' => 59.50, 'stock' => 90, 'sizes' => ['34'], 'colors' => ['black'], 'fit' => 'straight', 'material' => 'denim'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 11: H&M Basic Cotton T-Shirt ─────────────────────────────────────────
        $hmP = Product::updateOrCreate(
            ['slug' => Str::slug('H&M Regular Fit Cotton T-shirt')],
            [
                'name' => 'H&M Regular Fit Cotton T-shirt',
                'description' => 'A basic wardrobe essential made of soft 100% cotton jersey.',
                'short_description' => 'Basic H&M cotton tee',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $hmP->brand_id = $hm->id ?? null; $hmP->save();
        if ($mensShirts) $hmP->categories()->syncWithoutDetaching([$mensShirts->id]);

        $this->createVariants($hmP, [
            ['sku' => 'HM-TS-WHT-L', 'price' => 9.99, 'stock' => 300, 'sizes' => ['l'], 'colors' => ['white'], 'fit' => 'regular', 'material' => 'cotton'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 12: Uniqlo Ultra Light Down Jacket ──────────────────────────────────
        $uniqloP = Product::updateOrCreate(
            ['slug' => Str::slug('Uniqlo Ultra Light Down Jacket Women')],
            [
                'name' => 'Uniqlo Ultra Light Down Jacket Women',
                'description' => 'Incredibly lightweight, warm, and compact for everyday winter portability.',
                'short_description' => 'Compact Uniqlo down jacket',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $uniqloP->brand_id = $uniqlo->id ?? null; $uniqloP->save();
        if ($womensCoats) $uniqloP->categories()->syncWithoutDetaching([$womensCoats->id]);

        $this->createVariants($uniqloP, [
            ['sku' => 'UQ-JKT-NVY-S', 'price' => 69.90, 'stock' => 120, 'sizes' => ['s'], 'colors' => ['navy'], 'fit' => 'slim', 'material' => 'nylon'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 13: Tommy Hilfiger Polo Shirt ───────────────────────────────────────
        $tommyP = Product::updateOrCreate(
            ['slug' => Str::slug('Tommy Hilfiger Classic Fit Polo')],
            [
                'name' => 'Tommy Hilfiger Classic Fit Polo',
                'description' => 'Classic cotton pique polo with Tommy flag logo on the chest. Preppy and timeless.',
                'short_description' => 'Classic Tommy Hilfiger Polo',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $tommyP->brand_id = $tommy->id ?? null; $tommyP->save();
        if ($mensPolos) $tommyP->categories()->syncWithoutDetaching([$mensPolos->id]);

        $this->createVariants($tommyP, [
            ['sku' => 'TH-PL-RED-M', 'price' => 59.50, 'sale_price' => 39.50, 'stock' => 65, 'sizes' => ['m'], 'colors' => ['red'], 'fit' => 'regular', 'material' => 'cotton'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 14: Calvin Klein Underwear Men ───────────────────────────────────────
        $ckP = Product::updateOrCreate(
            ['slug' => Str::slug('Calvin Klein Cotton Stretch Boxer Briefs 3-Pack')],
            [
                'name' => 'Calvin Klein Cotton Stretch Boxer Briefs 3-Pack',
                'description' => 'Everyday comfort with signature Calvin Klein logo waistband and soft cotton blend.',
                'short_description' => 'CK Cotton stretch boxers 3-pack',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $ckP->brand_id = $ck->id ?? null; $ckP->save();
        if ($mensUnderwear) $ckP->categories()->syncWithoutDetaching([$mensUnderwear->id]);

        $this->createVariants($ckP, [
            ['sku' => 'CK-BB-BLK-L', 'price' => 42.50, 'stock' => 110, 'sizes' => ['l'], 'colors' => ['black'], 'fit' => 'athletic', 'material' => 'cotton-polyester'],
        ], $sizes, $colors, $fits, $materials);

        // ── Product 15: New Balance 574 Sneakers Women ──────────────────────────────────
        $nbP = Product::updateOrCreate(
            ['slug' => Str::slug('New Balance 574 Core Sneakers')],
            [
                'name' => 'New Balance 574 Core Sneakers',
                'description' => 'The ultimate everyday shoe with a retro silhouette and unbeatable comfort.',
                'short_description' => 'Classic NB 574 Lifestyle Sneakers',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $nbP->brand_id = $nb->id ?? null; $nbP->save();
        if ($womensCasualShoes) $nbP->categories()->syncWithoutDetaching([$womensCasualShoes->id]);

        $this->createVariants($nbP, [
            ['sku' => 'NB-574-GRY-8', 'price' => 89.99, 'stock' => 70, 'sizes' => ['8'], 'colors' => ['grey'], 'width' => 'medium', 'toe' => 'round-toe', 'closure' => 'lace-up', 'material' => 'suede'],
        ], $sizes, $colors, null, $materials, null, null, $widths, null, $toes, $closures);

        // ── Product 16: Dr. Martens 1460 Boots ──────────────────────────────────────────
        $drmartensP = Product::updateOrCreate(
            ['slug' => Str::slug('Dr Martens 1460 Smooth Leather Lace Up Boots')],
            [
                'name' => 'Dr Martens 1460 Smooth Leather Lace Up Boots',
                'description' => 'The original Docs with 8 eyes, grooved edges, yellow stitching, and a comfortable air-cushioned sole.',
                'short_description' => 'Icons of rebellion: 1460 Boots',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $drmartensP->brand_id = $drmartens->id ?? null; $drmartensP->save();
        if ($womensBoots) $drmartensP->categories()->syncWithoutDetaching([$womensBoots->id]);

        $this->createVariants($drmartensP, [
            ['sku' => 'DM-1460-BLK-9', 'price' => 170.00, 'stock' => 45, 'sizes' => ['9'], 'colors' => ['black'], 'width' => 'medium', 'heel' => 'low', 'toe' => 'round-toe', 'closure' => 'lace-up', 'material' => 'leather'],
        ], $sizes, $colors, null, $materials, null, null, $widths, $heels, $toes, $closures);

        // ── Product 17: Gymshark Vital Seamless Leggings ─────────────────────────────────
        $gymsharkP = Product::updateOrCreate(
            ['slug' => Str::slug('Gymshark Vital Seamless 2.0 Leggings')],
            [
                'name' => 'Gymshark Vital Seamless 2.0 Leggings',
                'description' => 'Do it all leggings with sweat-wicking tech and contouring seamless textures.',
                'short_description' => 'Gymshark Seamless performance leggings',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $gymsharkP->brand_id = $gymshark->id ?? null; $gymsharkP->save();
        if ($womensLeggings) $gymsharkP->categories()->syncWithoutDetaching([$womensLeggings->id]);

        $this->createVariants($gymsharkP, [
            ['sku' => 'GS-VS-TBL-M', 'price' => 50.00, 'sale_price' => 35.00, 'stock' => 135, 'sizes' => ['m'], 'colors' => ['teal'], 'fit' => 'athletic', 'activity' => 'training', 'compression' => 'high'],
        ], $sizes, $colors, $fits, null, $activities, $compressions);

        // ── Product 18: Under Armour Tech 2.0 T-Shirt ────────────────────────────────────
        $uaP = Product::updateOrCreate(
            ['slug' => Str::slug('Under Armour Tech 2.0 Short Sleeve T-Shirt')],
            [
                'name' => 'Under Armour Tech 2.0 Short Sleeve T-Shirt',
                'description' => 'Loose, light, and keeps you cool. Essential workout gear for men.',
                'short_description' => 'UA Tech 2.0 workout shirt',
                'is_visible' => true,
                'is_featured' => false,
            ]
        );
        $uaP->brand_id = $ua->id ?? null; $uaP->save();
        if ($mensActiveTees) $uaP->categories()->syncWithoutDetaching([$mensActiveTees->id]);

        $this->createVariants($uaP, [
            ['sku' => 'UA-T2-BLK-XL', 'price' => 25.00, 'stock' => 220, 'sizes' => ['xl'], 'colors' => ['black'], 'fit' => 'loose', 'activity' => 'training', 'material' => 'polyester'],
        ], $sizes, $colors, $fits, $materials, $activities);

        // ── Product 19: Michael Kors Jet Set Tote ───────────────────────────────────────
        $mkP = Product::updateOrCreate(
            ['slug' => Str::slug('Michael Kors Jet Set Large Saffiano Leather Tote Bag')],
            [
                'name' => 'Michael Kors Jet Set Large Saffiano Leather Tote Bag',
                'description' => 'Timeless large tote bag crafted from Saffiano leather, perfect for daily commuting.',
                'short_description' => 'MK Signature Saffiano Tote',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $mkP->brand_id = $mk->id ?? null; $mkP->save();
        if ($bagsTotes) $mkP->categories()->syncWithoutDetaching([$bagsTotes->id]);

        $this->createVariants($mkP, [
            ['sku' => 'MK-JS-BRN', 'price' => 298.00, 'sale_price' => 198.00, 'stock' => 25, 'colors' => ['brown'], 'material' => 'leather'],
            ['sku' => 'MK-JS-BLK', 'price' => 298.00, 'stock' => 40, 'colors' => ['black'], 'material' => 'leather'],
        ], $sizes, $colors, null, $materials);

        // ── Product 20: Fossil Nate Chronograph Watch ─────────────────────────────────────
        $fossilP = Product::updateOrCreate(
            ['slug' => Str::slug('Fossil Nate Chronograph Black Silicone Watch')],
            [
                'name' => 'Fossil Nate Chronograph Black Silicone Watch',
                'description' => 'Military-inspired, oversized watch with bold details and a comfortable silicone strap.',
                'short_description' => 'Fossil Nate oversized chronograph',
                'is_visible' => true,
                'is_featured' => true,
            ]
        );
        $fossilP->brand_id = $fossil->id ?? null; $fossilP->save();
        if ($watchesMen) $fossilP->categories()->syncWithoutDetaching([$watchesMen->id]);

        $this->createVariants($fossilP, [
            ['sku' => 'FS-NT-BLK-50MM', 'price' => 150.00, 'sale_price' => 112.50, 'stock' => 35, 'colors' => ['black'], 'case-size' => '50mm', 'band-material' => 'silicone'], // Assuming case sizes exist, we map close ones dynamically below if needed or just use default watch attributes if applicable
        ], $sizes, $colors, null, null, null, null, null, null, null, null, null, null, null, null, $caseSizes, $bandMaterials);

        $this->command->info('→ 20 realistic products + variants seeded across various brands and categories!');
    }

    /**
     * Helper to create variants with multiple attribute values
     */
    private function createVariants(Product $product, array $variantData, array $sizes, array $colors, ?array $fits = null, ?array $materials = null, ?array $activities = null, ?array $compressions = null, ?array $widths = null, ?array $heels = null, ?array $toes = null, ?array $closures = null, ?array $metals = null, ?array $gems = null, ?array $lengths = null, ?array $patterns = null, ?array $caseSizes = null, ?array $bandMaterials = null)
    {
        foreach ($variantData as $data) {
            $variant = $product->variants()->updateOrCreate(
                ['sku' => $data['sku']],
                [
                    'price' => $data['price'],
                    'sale_price' => $data['sale_price'] ?? null,
                    'stock_quantity' => $data['stock'],
                    'is_default' => $data['default'] ?? false,
                    'weight_grams' => 350,
                ]
            );

            // Always attach size & color
            if (isset($data['sizes'])) {
                foreach ($data['sizes'] as $s) {
                    if (isset($sizes[$s]))
                        $variant->attributeValues()->syncWithoutDetaching([$sizes[$s]]);
                }
            }
            if (isset($data['colors'])) {
                foreach ($data['colors'] as $c) {
                    if (isset($colors[$c]))
                        $variant->attributeValues()->syncWithoutDetaching([$colors[$c]]);
                }
            }

            // Optional attributes
            if ($fits && isset($data['fit']) && isset($fits[$data['fit']])) {
                $variant->attributeValues()->syncWithoutDetaching([$fits[$data['fit']]]);
            }
            if ($materials && isset($data['material']) && isset($materials[$data['material']])) {
                $variant->attributeValues()->syncWithoutDetaching([$materials[$data['material']]]);
            }
            if ($patterns && isset($data['pattern']) && isset($patterns[$data['pattern']])) {
                $variant->attributeValues()->syncWithoutDetaching([$patterns[$data['pattern']]]);
            }
            if ($activities && isset($data['activity']) && isset($activities[$data['activity']])) {
                $variant->attributeValues()->syncWithoutDetaching([$activities[$data['activity']]]);
            }
            if ($compressions && isset($data['compression']) && isset($compressions[$data['compression']])) {
                $variant->attributeValues()->syncWithoutDetaching([$compressions[$data['compression']]]);
            }
            if ($widths && isset($data['width']) && isset($widths[$data['width']])) {
                $variant->attributeValues()->syncWithoutDetaching([$widths[$data['width']]]);
            }
            if ($heels && isset($data['heel']) && isset($heels[$data['heel']])) {
                $variant->attributeValues()->syncWithoutDetaching([$heels[$data['heel']]]);
            }
            if ($toes && isset($data['toe']) && isset($toes[$data['toe']])) {
                $variant->attributeValues()->syncWithoutDetaching([$toes[$data['toe']]]);
            }
            if ($closures && isset($data['closure']) && isset($closures[$data['closure']])) {
                $variant->attributeValues()->syncWithoutDetaching([$closures[$data['closure']]]);
            }
            if ($metals && isset($data['metal']) && isset($metals[$data['metal']])) {
                $variant->attributeValues()->syncWithoutDetaching([$metals[$data['metal']]]);
            }
            if ($gems && isset($data['gem']) && isset($gems[$data['gem']])) {
                $variant->attributeValues()->syncWithoutDetaching([$gems[$data['gem']]]);
            }
            if ($lengths && isset($data['length']) && isset($lengths[$data['length']])) {
                $variant->attributeValues()->syncWithoutDetaching([$lengths[$data['length']]]);
            }
            if ($caseSizes && isset($data['case-size']) && isset($caseSizes[$data['case-size']])) {
                $variant->attributeValues()->syncWithoutDetaching([$caseSizes[$data['case-size']]]);
            }
            if ($bandMaterials && isset($data['band-material']) && isset($bandMaterials[$data['band-material']])) {
                $variant->attributeValues()->syncWithoutDetaching([$bandMaterials[$data['band-material']]]);
            }
        }
    }
}