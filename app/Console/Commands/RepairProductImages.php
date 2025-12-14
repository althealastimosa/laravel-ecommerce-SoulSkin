<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Product;
use Illuminate\Support\Facades\File;

class RepairProductImages extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'product:repair-images';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Attempt to repair product image paths by matching files in public/storage/products';

    public function handle()
    {
        $this->info('Scanning products...');

        $files = [];
        $dir = public_path('storage/products');
        if (File::exists($dir)) {
            foreach (File::files($dir) as $f) {
                $files[] = $f->getFilename();
            }
        }

        $products = Product::all();
        $updated = 0;

        foreach ($products as $product) {
            $basename = $product->image ? basename($product->image) : '';
            $expectedPath = $basename ? '/storage/products/' . $basename : null;

            // If file already exists on disk, ensure path uses /storage/products/<file>
            if ($basename && in_array($basename, $files)) {
                if ($product->image !== $expectedPath) {
                    $product->image = $expectedPath;
                    $product->save();
                    $this->line("Fixed product {$product->id} -> {$expectedPath}");
                    $updated++;
                }
                continue;
            }

            // Try match by suffix after first underscore (e.g. _dior.webp)
            if ($basename && strpos($basename, '_') !== false) {
                $suffix = substr($basename, strpos($basename, '_'));
                $matches = array_filter($files, function ($f) use ($suffix) {
                    return str_ends_with($f, $suffix);
                });

                if (count($matches) === 1) {
                    $newBasename = array_values($matches)[0];
                    $newPath = '/storage/products/' . $newBasename;
                    $product->image = $newPath;
                    $product->save();
                    $this->line("Updated product {$product->id} -> {$newPath}");
                    $updated++;
                    continue;
                }
            }

            // Try match by name parts (aggressive match)
            if ($product->name) {
                $parts = preg_split('/[^a-z0-9]+/', strtolower($product->name));
                $parts = array_filter($parts, function ($p) { return strlen($p) >= 2; });
                if (count($parts)) {
                    $matches = array_filter($files, function ($f) use ($parts) {
                        $lf = strtolower($f);
                        foreach ($parts as $part) {
                            if (str_contains($lf, $part)) return true;
                        }
                        return false;
                    });

                    if (count($matches) === 1) {
                        $newBasename = array_values($matches)[0];
                        $newPath = '/storage/products/' . $newBasename;
                        $product->image = $newPath;
                        $product->save();
                        $this->line("Matched by parts product {$product->id} -> {$newPath}");
                        $updated++;
                        continue;
                    }

                    // If multiple matches, pick the most recent by filename (numeric prefix), update anyway
                    if (count($matches) > 1) {
                        $vals = array_values($matches);
                        sort($vals, SORT_STRING);
                        $newBasename = end($vals);
                        $newPath = '/storage/products/' . $newBasename;
                        $product->image = $newPath;
                        $product->save();
                        $this->line("Ambiguous match; picked {$newBasename} for product {$product->id}");
                        $updated++;
                        continue;
                    }
                }
            }
        }

        $this->info("Done. {$updated} product(s) updated.");
        return 0;
    }
}
