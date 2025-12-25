<?php

namespace App\Http\Controllers;

use App\Services\CartService;
use Illuminate\Http\Request;

class CartController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function index()
    {
        $cart = $this->cartService->getCart();
        $items = $this->cartService->getItems();
        $subtotal = $this->cartService->getSubtotal();
        $total = $this->cartService->getTotal();
        $deposit = $this->cartService->getDeposit();
        $needsDeposit = $this->cartService->needsDeposit();

        return view('website.cart', compact('cart', 'items', 'subtotal', 'total', 'deposit', 'needsDeposit'));
    }

    public function add(Request $request)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'order_type' => 'required|in:instant,reservation',
            'variant_id' => 'nullable|exists:product_variants,id',
            'variant_combination' => 'nullable|string',
        ]);

        $result = $this->cartService->add(
            $request->product_id,
            $request->quantity,
            $request->variant_id,
            $request->variant_combination,
            $request->order_type
        );

        if ($result['success']) {
            return redirect()->back()->with('cart_added', $result['message']);
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function update(Request $request, $itemKey)
    {
        $request->validate([
            'quantity' => 'required|integer|min:0',
        ]);

        $result = $this->cartService->update($itemKey, $request->quantity);

        if ($result['success']) {
            return redirect()->back()->with('success', 'تم تحديث السلة');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function remove($itemKey)
    {
        $result = $this->cartService->remove($itemKey);

        if ($result['success']) {
            return redirect()->back()->with('success', 'تم حذف المنتج من السلة');
        }

        return redirect()->back()->with('error', $result['message']);
    }

    public function clear()
    {
        $this->cartService->clear();
        return redirect()->route('home')->with('success', 'تم تفريغ السلة');
    }
}
