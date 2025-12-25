<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\ProductStock;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class OrderController extends Controller
{
    public function index(Request $request)
    {
        $query = Order::with('items')->latest();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        // Filter by order type
        if ($request->has('order_type') && $request->order_type != '') {
            $query->where('order_type', $request->order_type);
        }

        // Search by customer name or phone
        if ($request->has('search') && $request->search != '') {
            $search = $request->search;
            $query->where(function($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('customer_phone', 'like', "%{$search}%");
            });
        }

        $orders = $query->paginate(20);

        return view('admin.orders.index', compact('orders'));
    }

    public function show($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $products = Product::where('is_active', 1)->with('variants', 'stock')->get();
        return view('admin.orders.show', compact('order', 'products'));
    }

    public function edit($id)
    {
        $order = Order::with('items.product')->findOrFail($id);
        $products = Product::where('is_active', 1)->with('variants', 'stock')->get();
        return view('admin.orders.edit', compact('order', 'products'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,confirmed,preparing,out_for_delivery,delivered,cancelled'
        ]);

        $order = Order::findOrFail($id);
        $order->status = $request->status;
        $order->save();

        return redirect()->back()->with('success', 'تم تحديث حالة الطلب بنجاح');
    }

    public function confirm($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'confirmed';
        $order->save();

        return redirect()->back()->with('success', 'تمت الموافقة على الطلب');
    }

    public function cancel($id)
    {
        $order = Order::findOrFail($id);
        $order->status = 'cancelled';
        $order->save();

        return redirect()->back()->with('success', 'تم إلغاء الطلب');
    }

    public function addItem(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required|exists:products,id',
            'quantity' => 'required|integer|min:1',
            'variant_id' => 'nullable|exists:product_variants,id',
            'variant_combination' => 'nullable|string',
        ]);

        $order = Order::findOrFail($id);
        $product = Product::findOrFail($request->product_id);

        // Check stock for instant orders
        if ($order->order_type === 'instant') {
            $stock = ProductStock::where('product_id', $request->product_id)
                ->where(function($q) use ($request) {
                    if ($request->variant_id) {
                        $q->where('product_variant_id', $request->variant_id)
                          ->where('variant_combination', $request->variant_combination);
                    } else {
                        $q->whereNull('product_variant_id');
                    }
                })
                ->first();

            if (!$stock || $stock->quantity < $request->quantity) {
                return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
            }
        }

        try {
            DB::beginTransaction();

            // Create order item
            $orderItem = OrderItem::create([
                'order_id' => $order->id,
                'product_id' => $request->product_id,
                'product_variant_id' => $request->variant_id,
                'variant_combination' => $request->variant_combination,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'quantity' => $request->quantity,
                'subtotal' => $product->price * $request->quantity,
            ]);

            // Deduct stock for instant orders
            if ($order->order_type === 'instant' && isset($stock)) {
                $stock->quantity -= $request->quantity;
                $stock->save();
            }

            // Recalculate order totals
            $this->recalculateOrderTotals($order);

            DB::commit();

            return redirect()->back()->with('success', 'تمت إضافة المنتج للطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function updateItem(Request $request, $orderId, $itemId)
    {
        $request->validate([
            'quantity' => 'required|integer|min:1',
        ]);

        $order = Order::findOrFail($orderId);
        $orderItem = OrderItem::where('order_id', $orderId)->findOrFail($itemId);

        try {
            DB::beginTransaction();

            $oldQuantity = $orderItem->quantity;
            $quantityDiff = $request->quantity - $oldQuantity;

            // Check stock for instant orders if quantity increased
            if ($order->order_type === 'instant' && $quantityDiff > 0) {
                $stock = ProductStock::where('product_id', $orderItem->product_id)
                    ->where(function($q) use ($orderItem) {
                        if ($orderItem->product_variant_id) {
                            $q->where('product_variant_id', $orderItem->product_variant_id)
                              ->where('variant_combination', $orderItem->variant_combination);
                        } else {
                            $q->whereNull('product_variant_id');
                        }
                    })
                    ->first();

                if (!$stock || $stock->quantity < $quantityDiff) {
                    DB::rollBack();
                    return redirect()->back()->with('error', 'الكمية المطلوبة غير متوفرة في المخزون');
                }

                // Update stock
                $stock->quantity -= $quantityDiff;
                $stock->save();
            } elseif ($order->order_type === 'instant' && $quantityDiff < 0) {
                // Return stock if quantity decreased
                $stock = ProductStock::where('product_id', $orderItem->product_id)
                    ->where(function($q) use ($orderItem) {
                        if ($orderItem->product_variant_id) {
                            $q->where('product_variant_id', $orderItem->product_variant_id)
                              ->where('variant_combination', $orderItem->variant_combination);
                        } else {
                            $q->whereNull('product_variant_id');
                        }
                    })
                    ->first();

                if ($stock) {
                    $stock->quantity += abs($quantityDiff);
                    $stock->save();
                }
            }

            // Update order item
            $orderItem->quantity = $request->quantity;
            $orderItem->subtotal = $orderItem->product_price * $request->quantity;
            $orderItem->save();

            // Recalculate order totals
            $this->recalculateOrderTotals($order);

            DB::commit();

            return redirect()->back()->with('success', 'تم تحديث الكمية بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    public function removeItem($orderId, $itemId)
    {
        $order = Order::findOrFail($orderId);
        $orderItem = OrderItem::where('order_id', $orderId)->findOrFail($itemId);

        try {
            DB::beginTransaction();

            // Return stock for instant orders
            if ($order->order_type === 'instant') {
                $stock = ProductStock::where('product_id', $orderItem->product_id)
                    ->where(function($q) use ($orderItem) {
                        if ($orderItem->product_variant_id) {
                            $q->where('product_variant_id', $orderItem->product_variant_id)
                              ->where('variant_combination', $orderItem->variant_combination);
                        } else {
                            $q->whereNull('product_variant_id');
                        }
                    })
                    ->first();

                if ($stock) {
                    $stock->quantity += $orderItem->quantity;
                    $stock->save();
                }
            }

            // Delete order item
            $orderItem->delete();

            // Recalculate order totals
            $this->recalculateOrderTotals($order);

            DB::commit();

            return redirect()->back()->with('success', 'تم حذف المنتج من الطلب بنجاح');

        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->back()->with('error', 'حدث خطأ: ' . $e->getMessage());
        }
    }

    protected function recalculateOrderTotals($order)
    {
        $order->load('items');

        $subtotal = $order->items->sum('subtotal');
        $total = $subtotal + $order->delivery_fee;
        $depositAmount = $total > 1000 ? $total * 0.5 : null;

        $order->subtotal = $subtotal;
        $order->total = $total;
        $order->deposit_amount = $depositAmount;
        $order->save();
    }
}
