<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;

class WebsiteController extends Controller
{
    public function index()
    {
        return view('website.index');
    }

    public function instant(Request $request)
    {
        // Get products with stock > 0
        $query = Product::with(['images', 'variants.options', 'stock'])
            ->where('is_active', true)
            ->whereHas('stock', function($query) {
                $query->where('quantity', '>', 0);
            });

        // Apply sorting
        switch($request->get('sort', 'desc')) {
            case 'asc':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return view('website.instant', compact('products'));
    }

    public function reservation(Request $request)
    {
        // Get all active products
        $query = Product::with(['images', 'variants.options'])
            ->where('is_active', true);

        // Apply sorting
        switch($request->get('sort', 'desc')) {
            case 'asc':
                $query->oldest();
                break;
            case 'price_asc':
                $query->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $query->orderBy('price', 'desc');
                break;
            default:
                $query->latest();
        }

        $products = $query->paginate(12);

        return view('website.reservation', compact('products'));
    }

    public function about()
    {
        return view('website.about');
    }
}
