@extends('layouts.website')

@section('title', 'السلة - Dasa\'s Cake')

@push('styles')
<style>
    .page-header {
        background: var(--light-bg);
        padding: 2rem 0;
        border-radius: 12px;
        margin-bottom: 2rem;
        text-align: center;
    }

    .page-header h1 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 0.5rem;
    }

    .order-type-badge {
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
        padding: 0.5rem 1.25rem;
        border-radius: 25px;
        font-size: 0.95rem;
        font-weight: 600;
        margin-bottom: 1.5rem;
    }

    .order-type-badge.instant {
        background: #28a745;
        color: #fff;
    }

    .order-type-badge.reservation {
        background: var(--secondary-color);
        color: #fff;
    }

    .cart-item {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.25rem;
        transition: all 0.3s ease;
    }

    .cart-item:hover {
        border-color: var(--primary-color);
        box-shadow: 0 5px 15px rgba(245, 71, 107, 0.1);
    }

    .cart-item-image {
        width: 120px;
        height: 120px;
        object-fit: cover;
        border-radius: 10px;
        border: 2px solid var(--border-color);
    }

    .cart-item-placeholder {
        width: 120px;
        height: 120px;
        background: var(--light-bg);
        border-radius: 10px;
        border: 2px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-name {
        font-size: 1.15rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .product-variant {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.75rem;
    }

    .product-price {
        color: var(--primary-color);
        font-size: 1.1rem;
        font-weight: 700;
    }

    .quantity-controls {
        display: flex;
        align-items: center;
        gap: 0.5rem;
        background: var(--light-bg);
        border-radius: 10px;
        padding: 0.25rem;
        border: 2px solid var(--border-color);
    }

    .quantity-btn {
        width: 36px;
        height: 36px;
        border: none;
        background: #fff;
        color: var(--primary-color);
        border-radius: 8px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.1rem;
    }

    .quantity-btn:hover {
        background: var(--primary-color);
        color: #fff;
        transform: scale(1.05);
    }

    .quantity-btn:active {
        transform: scale(0.95);
    }

    .quantity-display {
        min-width: 50px;
        text-align: center;
        font-weight: 600;
        color: var(--secondary-color);
        font-size: 1.05rem;
    }

    .item-total {
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--secondary-color);
    }

    .remove-btn {
        width: 40px;
        height: 40px;
        border: 2px solid #dc3545;
        background: #fff;
        color: #dc3545;
        border-radius: 10px;
        display: flex;
        align-items: center;
        justify-content: center;
        cursor: pointer;
        transition: all 0.2s;
        font-size: 1.25rem;
    }

    .remove-btn:hover {
        background: #dc3545;
        color: #fff;
        transform: scale(1.05);
    }

    .summary-card {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        position: sticky;
        top: 20px;
    }

    .summary-card h4 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        padding-bottom: 1rem;
        border-bottom: 2px solid var(--border-color);
    }

    .summary-row {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding: 0.875rem 0;
        font-size: 1rem;
    }

    .summary-row:not(:last-child) {
        border-bottom: 1px dashed var(--border-color);
    }

    .summary-row.total {
        font-size: 1.35rem;
        font-weight: 700;
        color: var(--primary-color);
        padding-top: 1.25rem;
        margin-top: 0.5rem;
        border-top: 2px solid var(--border-color);
    }

    .deposit-alert {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%);
        border: 2px solid #ffc107;
        border-radius: 12px;
        padding: 1.25rem;
        margin-top: 1.5rem;
    }

    .deposit-alert strong {
        color: #856404;
    }

    .deposit-amount {
        font-size: 1.5rem;
        font-weight: 700;
        color: #856404;
        margin-top: 0.5rem;
    }

    .clear-cart-btn {
        border: 2px solid #dc3545;
        color: #dc3545;
        background: #fff;
        padding: 0.625rem 1.5rem;
        border-radius: 10px;
        font-weight: 600;
        transition: all 0.3s;
    }

    .clear-cart-btn:hover {
        background: #dc3545;
        color: #fff;
        border-color: #dc3545;
        transform: translateY(-2px);
    }

    .empty-cart {
        text-align: center;
        padding: 4rem 0;
    }

    .empty-cart i {
        font-size: 80px;
        opacity: 0.2;
        color: var(--primary-color);
        margin-bottom: 1.5rem;
    }

    @media (max-width: 768px) {
        .cart-item {
            padding: 1rem;
        }

        .cart-item-image,
        .cart-item-placeholder {
            width: 80px;
            height: 80px;
        }

        .item-total {
            margin-top: 0.75rem;
        }
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="ph-duotone ph-shopping-cart me-2"></i>سلة التسوق</h1>
</div>

@if(empty($items))
    <div class="empty-cart">
        <img src="{{asset('empty-cart.png')}}" alt="">
        <h3 class="text-muted mb-3">السلة فارغة</h3>
        <p class="text-muted mb-4">ابدأ بإضافة منتجات من صفحة التسليم الفوري أو الحجز</p>
    </div>
@else
    <div class="row">
        <div class="col-lg-8">
            <!-- Order Type Badge -->
            <span class="order-type-badge {{ $cart['order_type'] }}">
                @if($cart['order_type'] === 'instant')
                    <i class="ph-duotone ph-lightning"></i>
                    <span>تسليم فوري</span>
                @else
                    <i class="ph-duotone ph-calendar-check"></i>
                    <span>حجز</span>
                @endif
            </span>

            <!-- Cart Items -->
            @foreach($items as $key => $item)
                <div class="cart-item">
                    <div class="row align-items-center g-3">
                        <!-- Product Image -->
                        <div class="col-auto">
                            @if($item['product_image'])
                                <img src="{{ asset('storage/' . $item['product_image']) }}"
                                     alt="{{ $item['product_name'] }}"
                                     class="cart-item-image">
                            @else
                                <div class="cart-item-placeholder">
                                    <i class="ph-duotone ph-image" style="font-size: 36px; opacity: 0.3;"></i>
                                </div>
                            @endif
                        </div>

                        <!-- Product Info -->
                        <div class="col">
                            <h5 class="product-name">{{ $item['product_name'] }}</h5>
                            @if($item['variant_combination'])
                                @php
                                    $variants = json_decode($item['variant_combination'], true);
                                @endphp
                                @if($variants && is_array($variants))
                                    <div class="product-variant">
                                        <i class="ph-duotone ph-tag me-1"></i>
                                        @foreach($variants as $key => $value)
                                            <span>{{ $key }}: {{ $value }}</span>
                                            @if(!$loop->last) | @endif
                                        @endforeach
                                    </div>
                                @endif
                            @endif
                            <div class="product-price">
                                {{ number_format($item['product_price'], 2) }} د.ل
                            </div>
                        </div>

                        <!-- Quantity Controls -->
                        <div class="col-12 col-md-auto">
                            <form action="{{ route('cart.update', $key) }}" method="POST" class="quantity-form">
                                @csrf
                                @method('PATCH')
                                <input type="hidden" name="quantity" value="{{ $item['quantity'] }}" class="quantity-input">
                                <div class="quantity-controls">
                                    <button type="button" class="quantity-btn decrease-btn">
                                        <i class="ph-duotone ph-minus"></i>
                                    </button>
                                    <span class="quantity-display">{{ $item['quantity'] }}</span>
                                    <button type="button" class="quantity-btn increase-btn">
                                        <i class="ph-duotone ph-plus"></i>
                                    </button>
                                </div>
                            </form>
                        </div>

                        <!-- Item Total -->
                        <div class="col-12 col-md-auto text-center">
                            <div class="item-total">
                                {{ number_format($item['product_price'] * $item['quantity'], 2) }} د.ل
                            </div>
                        </div>

                        <!-- Remove Button -->
                        <div class="col-auto">
                            <form action="{{ route('cart.remove', $key) }}" method="POST" class="remove-item-form">
                                @csrf
                                @method('DELETE')
                                <button type="button" class="remove-btn remove-item-btn" title="حذف المنتج">
                                    <i class="ph-duotone ph-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            @endforeach

            <!-- Clear Cart -->
            <div class="text-end mt-3">
                <form action="{{ route('cart.clear') }}" method="POST" class="d-inline" id="clear-cart-form">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="clear-cart-btn" id="clear-cart-btn">
                        <i class="ph-duotone ph-trash me-2"></i>
                        تفريغ السلة
                    </button>
                </form>
            </div>
        </div>

        <!-- Summary Card -->
        <div class="col-lg-4 mt-4 mt-lg-0">
            <div class="summary-card">
                <h4>
                    <i class="ph-duotone ph-receipt me-2"></i>
                    ملخص الطلب
                </h4>

                <div class="summary-row">
                    <span>المجموع الفرعي</span>
                    <strong>{{ number_format($subtotal, 2) }} د.ل</strong>
                </div>

                <div class="summary-row">
                    <span>رسوم التوصيل</span>
                    <strong>10.00 د.ل</strong>
                </div>

                <div class="summary-row total">
                    <span>الإجمالي</span>
                    <strong>{{ number_format($total, 2) }} د.ل</strong>
                </div>

                @if($needsDeposit)
                    <div class="deposit-alert">
                        <div class="d-flex align-items-center mb-2">
                            <i class="ph-duotone ph-warning-circle me-2" style="font-size: 1.5rem; color: #856404;"></i>
                            <strong>عربون مطلوب</strong>
                        </div>
                        <p class="mb-2" style="color: #856404; font-size: 0.95rem;">
                            الطلب يتجاوز 1000 دينار، يجب دفع عربون:
                        </p>
                        <div class="deposit-amount">
                            {{ number_format($deposit, 2) }} د.ل
                        </div>
                        <small style="color: #856404;">(50% من إجمالي الطلب)</small>
                    </div>
                @endif

                <a href="{{ route('checkout') }}" class="btn btn-primary w-100 mt-3" style="padding: 0.875rem;">
                    <i class="ph-duotone ph-check-circle me-2"></i>
                    إتمام الطلب
                </a>

                <a href="{{ $cart['order_type'] === 'instant' ? route('instant') : route('reservation') }}"
                   class="btn btn-outline-secondary w-100 mt-2" style="padding: 0.875rem;">
                    <i class="ph-duotone ph-arrow-right me-2"></i>
                    متابعة التسوق
                </a>
            </div>
        </div>
    </div>
@endif

@push('scripts')
<script>
// Quantity controls
document.querySelectorAll('.quantity-form').forEach(form => {
    const input = form.querySelector('.quantity-input');
    const display = form.querySelector('.quantity-display');
    const decreaseBtn = form.querySelector('.decrease-btn');
    const increaseBtn = form.querySelector('.increase-btn');

    decreaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(input.value);
        if (currentValue > 1) {
            currentValue--;
            input.value = currentValue;
            display.textContent = currentValue;
            form.submit();
        }
    });

    increaseBtn.addEventListener('click', function() {
        let currentValue = parseInt(input.value);
        currentValue++;
        input.value = currentValue;
        display.textContent = currentValue;
        form.submit();
    });
});

// Remove item confirmation
document.querySelectorAll('.remove-item-btn').forEach(button => {
    button.addEventListener('click', function(e) {
        e.preventDefault();
        const form = this.closest('.remove-item-form');

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'هل تريد حذف هذا المنتج من السلة؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، احذف',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                form.submit();
            }
        });
    });
});

// Clear cart confirmation
const clearCartBtn = document.getElementById('clear-cart-btn');
if (clearCartBtn) {
    clearCartBtn.addEventListener('click', function(e) {
        e.preventDefault();

        Swal.fire({
            title: 'هل أنت متأكد؟',
            text: 'هل تريد تفريغ السلة بالكامل؟',
            icon: 'warning',
            showCancelButton: true,
            confirmButtonText: 'نعم، أفرغ السلة',
            cancelButtonText: 'إلغاء',
            reverseButtons: true
        }).then((result) => {
            if (result.isConfirmed) {
                document.getElementById('clear-cart-form').submit();
            }
        });
    });
}
</script>
@endpush
@endsection
