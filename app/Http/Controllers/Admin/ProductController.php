<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\ProductImage;
use App\Models\ProductVariant;
use App\Models\ProductVariantOption;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;

class ProductController extends Controller
{
    public function index(Request $request)
    {
        $query = Product::with(['images', 'variants', 'stock']);

        if ($request->filled('search')) {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('name', 'like', "%{$search}%")
                  ->orWhere('description', 'like', "%{$search}%");
            });
        }

        $products = $query->latest()->paginate(15)->withQueryString();

        return view('admin.products.index', compact('products'));
    }

    public function create()
    {
        return view('admin.products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|max:10240',
            'has_variants' => 'boolean',
            'is_active' => 'boolean',
            'gallery_images.*' => 'nullable|image|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $product = new Product();
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->has_variants = $request->input('has_variants', 0);
            $product->is_active = $request->input('is_active', 0);

            if ($request->hasFile('main_image')) {
                $path = $request->file('main_image')->store('products', 'public');
                $product->main_image = $path;
            }

            $product->save();

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $index,
                    ]);
                }
            }

            if ($request->input('has_variants') && $request->variants) {
                foreach ($request->variants as $variantIndex => $variantData) {
                    if (!empty($variantData['name'])) {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'sort_order' => $variantIndex,
                        ]);

                        if (!empty($variantData['options'])) {
                            foreach ($variantData['options'] as $optionIndex => $optionValue) {
                                if (!empty($optionValue)) {
                                    ProductVariantOption::create([
                                        'product_variant_id' => $variant->id,
                                        'value' => $optionValue,
                                        'sort_order' => $optionIndex,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم إضافة المنتج بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إضافة المنتج: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function show($id)
    {
        $product = Product::with(['images', 'variants.options', 'stock'])->findOrFail($id);
        return view('admin.products.show', compact('product'));
    }

    public function edit($id)
    {
        $product = Product::with(['images', 'variants.options'])->findOrFail($id);
        return view('admin.products.edit', compact('product'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'description' => 'nullable|string',
            'price' => 'required|numeric|min:0',
            'main_image' => 'nullable|image|max:10240',
            'has_variants' => 'boolean',
            'is_active' => 'boolean',
            'gallery_images.*' => 'nullable|image|max:10240',
        ]);

        try {
            DB::beginTransaction();

            $product = Product::findOrFail($id);
            $product->name = $request->name;
            $product->description = $request->description;
            $product->price = $request->price;
            $product->has_variants = $request->input('has_variants', 0);
            $product->is_active = $request->input('is_active', 0);

            if ($request->hasFile('main_image')) {
                if ($product->main_image) {
                    Storage::disk('public')->delete($product->main_image);
                }
                $path = $request->file('main_image')->store('products', 'public');
                $product->main_image = $path;
            }

            $product->save();

            if ($request->hasFile('gallery_images')) {
                foreach ($request->file('gallery_images') as $index => $image) {
                    $path = $image->store('products/gallery', 'public');
                    ProductImage::create([
                        'product_id' => $product->id,
                        'image_path' => $path,
                        'sort_order' => $product->images()->count() + $index,
                    ]);
                }
            }

            $product->variants()->delete();

            if ($request->input('has_variants') && $request->variants) {
                foreach ($request->variants as $variantIndex => $variantData) {
                    if (!empty($variantData['name'])) {
                        $variant = ProductVariant::create([
                            'product_id' => $product->id,
                            'name' => $variantData['name'],
                            'sort_order' => $variantIndex,
                        ]);

                        if (!empty($variantData['options'])) {
                            foreach ($variantData['options'] as $optionIndex => $optionValue) {
                                if (!empty($optionValue)) {
                                    ProductVariantOption::create([
                                        'product_variant_id' => $variant->id,
                                        'value' => $optionValue,
                                        'sort_order' => $optionIndex,
                                    ]);
                                }
                            }
                        }
                    }
                }
            }

            DB::commit();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم تحديث المنتج بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المنتج: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $product = Product::findOrFail($id);

            if ($product->main_image) {
                Storage::disk('public')->delete($product->main_image);
            }

            foreach ($product->images as $image) {
                Storage::disk('public')->delete($image->image_path);
            }

            $product->delete();

            return redirect()->route('admin.products.index')
                ->with('success', 'تم حذف المنتج بنجاح');

        } catch (\Exception $e) {
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء حذف المنتج: ' . $e->getMessage());
        }
    }

    public function deleteImage($imageId)
    {
        try {
            $image = ProductImage::findOrFail($imageId);
            Storage::disk('public')->delete($image->image_path);
            $image->delete();

            return response()->json(['success' => true, 'message' => 'تم حذف الصورة بنجاح']);
        } catch (\Exception $e) {
            return response()->json(['success' => false, 'message' => 'حدث خطأ أثناء حذف الصورة'], 500);
        }
    }

    public function updateStock(Request $request, $id)
    {
        $product = Product::with(['variants.options'])->findOrFail($id);

        try {
            DB::beginTransaction();

            if (!$product->has_variants) {
                $request->validate([
                    'quantity' => 'required|integer|min:0',
                ]);

                ProductStock::updateOrCreate(
                    [
                        'product_id' => $product->id,
                        'product_variant_id' => null,
                        'variant_combination' => null,
                    ],
                    [
                        'quantity' => $request->quantity,
                    ]
                );
            } else {
                $request->validate([
                    'stock' => 'required|array',
                    'stock.*.*' => 'required|integer|min:0',
                ]);

                foreach ($request->stock as $variantId => $options) {
                    $variant = $product->variants->find($variantId);
                    if (!$variant) continue;

                    foreach ($options as $optionId => $quantity) {
                        $option = $variant->options->find($optionId);
                        if (!$option) continue;

                        $variantCombination = json_encode([$variant->name => $option->value]);

                        ProductStock::updateOrCreate(
                            [
                                'product_id' => $product->id,
                                'product_variant_id' => $variant->id,
                                'variant_combination' => $variantCombination,
                            ],
                            [
                                'quantity' => $quantity,
                            ]
                        );
                    }
                }
            }

            DB::commit();

            return redirect()->back()
                ->with('success', 'تم تحديث المخزون بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء تحديث المخزون: ' . $e->getMessage());
        }
    }
}
