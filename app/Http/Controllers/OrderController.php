<?php

namespace App\Http\Controllers;

use App\Mail\NewOrderNotification;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\ProductStock;
use App\Services\CartService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;

class OrderController extends Controller
{
    protected $cartService;

    public function __construct(CartService $cartService)
    {
        $this->cartService = $cartService;
    }

    public function checkout()
    {
        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('home')->with('error', 'السلة فارغة');
        }

        $cart = $this->cartService->getCart();
        $subtotal = $this->cartService->getSubtotal();
        $total = $this->cartService->getTotal();
        $deposit = $this->cartService->getDeposit();
        $needsDeposit = $this->cartService->needsDeposit();
        $orderType = $this->cartService->getOrderType();

        return view('website.checkout', compact('cart', 'items', 'subtotal', 'total', 'deposit', 'needsDeposit', 'orderType'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'customer_phone' => 'required|string|max:20',
            'delivery_address' => 'required|string',
            'delivery_type' => 'required|in:male,female',
            'payment_method' => 'required|in:cash,bank_transfer',
            'delivery_date' => 'nullable|date|after:today',
        ]);

        $items = $this->cartService->getItems();

        if (empty($items)) {
            return redirect()->route('home')->with('error', 'السلة فارغة');
        }

        $orderType = $this->cartService->getOrderType();

        // Validate delivery date for reservations
        if ($orderType === 'reservation' && !$request->delivery_date) {
            return redirect()->back()
                ->with('error', 'يجب تحديد تاريخ التسليم للحجز')
                ->withInput();
        }

        try {
            DB::beginTransaction();

            $subtotal = $this->cartService->getSubtotal();
            $deliveryFee = 10;
            $total = $subtotal + $deliveryFee;
            $depositAmount = $total > 1000 ? $total * 0.5 : null;

            // Create order
            $order = Order::create([
                'customer_name' => $request->customer_name,
                'customer_phone' => $request->customer_phone,
                'delivery_address' => $request->delivery_address,
                'delivery_type' => $request->delivery_type,
                'delivery_fee' => $deliveryFee,
                'order_type' => $orderType,
                'delivery_date' => $orderType === 'reservation' ? $request->delivery_date : null,
                'payment_method' => $request->payment_method,
                'deposit_amount' => $depositAmount,
                'subtotal' => $subtotal,
                'total' => $total,
                'status' => 'pending',
            ]);

            // Create order items and update stock for instant orders
            foreach ($items as $item) {
                OrderItem::create([
                    'order_id' => $order->id,
                    'product_id' => $item['product_id'],
                    'product_variant_id' => $item['variant_id'],
                    'variant_combination' => $item['variant_combination'],
                    'product_name' => $item['product_name'],
                    'product_price' => $item['product_price'],
                    'quantity' => $item['quantity'],
                    'subtotal' => $item['product_price'] * $item['quantity'],
                ]);

                // Deduct stock for instant orders
                if ($orderType === 'instant') {
                    $stock = ProductStock::where('product_id', $item['product_id'])
                        ->where(function($q) use ($item) {
                            if ($item['variant_id']) {
                                $q->where('product_variant_id', $item['variant_id'])
                                  ->where('variant_combination', $item['variant_combination']);
                            } else {
                                $q->whereNull('product_variant_id');
                            }
                        })
                        ->first();

                    if ($stock) {
                        $stock->quantity -= $item['quantity'];
                        $stock->save();
                    }
                }
            }

            DB::commit();

            // Send email notification to admin
            try {
                $adminEmail = config('mail.admin_email', env('ADMIN_EMAIL'));
                if ($adminEmail) {
                    Mail::to($adminEmail)->send(new NewOrderNotification($order));
                }
            } catch (\Exception $e) {
                // Log the error but don't fail the order
                \Log::error('Failed to send order notification email: ' . $e->getMessage());
            }

            // Clear cart
            $this->cartService->clear();

            return redirect()->route('order.complete', $order->id);

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()
                ->with('error', 'حدث خطأ أثناء إرسال الطلب: ' . $e->getMessage())
                ->withInput();
        }
    }

    public function complete($id)
    {
        $order = Order::findOrFail($id);
        return view('website.order-complete', compact('order'));
    }

    public function track(Request $request)
    {
        $order = null;

        if ($request->has('order_id') && $request->order_id) {
            $order = Order::find($request->order_id);
        }

        return view('website.track-order', compact('order'));
    }
}
