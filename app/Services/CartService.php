<?php

namespace App\Services;

use App\Models\Product;
use App\Models\ProductVariant;
use Illuminate\Support\Facades\Session;

class CartService
{
    protected $sessionKey = 'cart';

    public function add($productId, $quantity = 1, $variantId = null, $variantCombination = null, $orderType = 'instant')
    {
        $cart = $this->getCart();

        // Check if cart is empty or same order type
        if (empty($cart['items'])) {
            $cart['order_type'] = $orderType;
        } elseif ($cart['order_type'] !== $orderType) {
            return [
                'success' => false,
                'message' => 'لا يمكن خلط منتجات التسليم الفوري والحجز في نفس الطلب',
            ];
        }

        $product = Product::find($productId);
        if (!$product) {
            return ['success' => false, 'message' => 'المنتج غير موجود'];
        }

        // Check stock for instant orders
        if ($orderType === 'instant') {
            $stock = $product->stock()
                ->where(function($q) use ($variantId, $variantCombination) {
                    if ($variantId) {
                        $q->where('product_variant_id', $variantId)
                          ->where('variant_combination', $variantCombination);
                    } else {
                        $q->whereNull('product_variant_id');
                    }
                })
                ->first();

            if (!$stock || $stock->quantity < $quantity) {
                return ['success' => false, 'message' => 'الكمية المطلوبة غير متوفرة في المخزون'];
            }
        }

        // Create a unique key for the cart item using MD5 hash to avoid URL encoding issues
        $keyData = $productId . '|' . ($variantId ?? 'no') . '|' . ($variantCombination ?? 'no');
        $itemKey = md5($keyData);

        if (isset($cart['items'][$itemKey])) {
            $cart['items'][$itemKey]['quantity'] += $quantity;
        } else {
            $cart['items'][$itemKey] = [
                'product_id' => $productId,
                'product_name' => $product->name,
                'product_price' => $product->price,
                'product_image' => $product->main_image,
                'quantity' => $quantity,
                'variant_id' => $variantId,
                'variant_combination' => $variantCombination,
                'order_type' => $orderType,
            ];
        }

        $this->saveCart($cart);

        return ['success' => true, 'message' => 'تمت الإضافة إلى السلة بنجاح'];
    }

    public function update($itemKey, $quantity)
    {
        $cart = $this->getCart();

        // Debug logging
        \Log::info('Cart Update Debug', [
            'received_key' => $itemKey,
            'available_keys' => array_keys($cart['items']),
            'key_exists' => isset($cart['items'][$itemKey])
        ]);

        if (isset($cart['items'][$itemKey])) {
            if ($quantity <= 0) {
                unset($cart['items'][$itemKey]);
            } else {
                $cart['items'][$itemKey]['quantity'] = $quantity;
            }

            $this->saveCart($cart);
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'المنتج غير موجود في السلة'];
    }

    public function remove($itemKey)
    {
        $cart = $this->getCart();

        if (isset($cart['items'][$itemKey])) {
            unset($cart['items'][$itemKey]);
            $this->saveCart($cart);
            return ['success' => true];
        }

        return ['success' => false, 'message' => 'المنتج غير موجود في السلة'];
    }

    public function clear()
    {
        Session::forget($this->sessionKey);
        return ['success' => true];
    }

    public function getCart()
    {
        $cart = Session::get($this->sessionKey, ['items' => [], 'order_type' => null]);

        // Migrate old cart keys to new MD5 format
        $cart = $this->migrateOldCartKeys($cart);

        return $cart;
    }

    protected function migrateOldCartKeys($cart)
    {
        if (empty($cart['items'])) {
            return $cart;
        }

        $migratedItems = [];
        $needsMigration = false;

        foreach ($cart['items'] as $oldKey => $item) {
            // Check if key is already MD5 (32 characters, alphanumeric)
            if (strlen($oldKey) === 32 && ctype_alnum($oldKey)) {
                // Already migrated
                $migratedItems[$oldKey] = $item;
            } else {
                // Old format - needs migration
                $needsMigration = true;
                $keyData = $item['product_id'] . '|' . ($item['variant_id'] ?? 'no') . '|' . ($item['variant_combination'] ?? 'no');
                $newKey = md5($keyData);
                $migratedItems[$newKey] = $item;
            }
        }

        if ($needsMigration) {
            $cart['items'] = $migratedItems;
            $this->saveCart($cart);
        }

        return $cart;
    }

    public function getItems()
    {
        $cart = $this->getCart();
        return $cart['items'] ?? [];
    }

    public function getOrderType()
    {
        $cart = $this->getCart();
        return $cart['order_type'];
    }

    public function getCount()
    {
        $items = $this->getItems();
        return array_sum(array_column($items, 'quantity'));
    }

    public function getSubtotal()
    {
        $items = $this->getItems();
        $subtotal = 0;

        foreach ($items as $item) {
            $subtotal += $item['product_price'] * $item['quantity'];
        }

        return $subtotal;
    }

    public function getTotal()
    {
        $subtotal = $this->getSubtotal();
        $deliveryFee = 10; // رسوم التوصيل الثابتة

        return $subtotal + $deliveryFee;
    }

    public function getDeposit()
    {
        $total = $this->getTotal();

        if ($total > 1000) {
            return $total * 0.5; // 50% عربون
        }

        return null;
    }

    public function needsDeposit()
    {
        return $this->getTotal() > 1000;
    }

    protected function saveCart($cart)
    {
        Session::put($this->sessionKey, $cart);
    }
}
