@extends('layouts.app')

@section('title', 'تفاصيل الطلب #' . $order->id)

@section('content')
   <div class="row">
            <!-- Order Status Card -->
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h5>حالة الطلب</h5>
                    </div>
                    <div class="card-body">
                        @php
                            $statusColors = [
                                'pending' => 'warning',
                                'confirmed' => 'primary',
                                'preparing' => 'info',
                                'out_for_delivery' => 'secondary',
                                'delivered' => 'success',
                                'cancelled' => 'danger'
                            ];
                            $statusLabels = [
                                'pending' => 'قيد الانتظار',
                                'confirmed' => 'تمت الموافقة',
                                'preparing' => 'قيد التحضير',
                                'out_for_delivery' => 'تم التسليم للمندوب',
                                'delivered' => 'تم التوصيل',
                                'cancelled' => 'ملغي'
                            ];
                        @endphp

                        <div class="mb-3">
                            <span class="badge bg-{{ $statusColors[$order->status] }} fs-5">
                                {{ $statusLabels[$order->status] }}
                            </span>
                        </div>

                        <!-- Status Update Form -->
                        @if($order->status != 'cancelled' && $order->status != 'delivered')
                            <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST">
                                @csrf
                                @method('PATCH')
                                <div class="mb-3">
                                    <label class="form-label">تحديث الحالة</label>
                                    <select name="status" class="form-select" required>
                                        <option value="">اختر الحالة</option>
                                        @if($order->status == 'pending')
                                            <option value="confirmed">تأكيد الطلب</option>
                                            <option value="cancelled">إلغاء الطلب</option>
                                        @endif
                                        @if($order->status == 'confirmed')
                                            <option value="preparing">قيد التحضير</option>
                                            <option value="cancelled">إلغاء الطلب</option>
                                        @endif
                                        @if($order->status == 'preparing')
                                            <option value="out_for_delivery">تسليم للمندوب</option>
                                        @endif
                                        @if($order->status == 'out_for_delivery')
                                            <option value="delivered">تم التوصيل</option>
                                        @endif
                                    </select>
                                </div>
                                <button type="submit" class="btn btn-primary w-100">
                                    <i class="ti ti-check"></i> تحديث الحالة
                                </button>
                            </form>
                        @endif

                        <!-- Quick Actions -->
                        @if($order->status == 'pending')
                            <div class="mt-3">
                                <form action="{{ route('admin.orders.confirm', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-success w-100 mb-2">
                                        <i class="ti ti-check"></i> موافقة سريعة
                                    </button>
                                </form>
                                <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="d-inline">
                                    @csrf
                                    <button type="submit" class="btn btn-danger w-100" onclick="return confirm('هل أنت متأكد من إلغاء الطلب؟')">
                                        <i class="ti ti-x"></i> إلغاء الطلب
                                    </button>
                                </form>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Order Type -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>نوع الطلب</h5>
                    </div>
                    <div class="card-body">
                        @if($order->order_type == 'instant')
                            <span class="badge bg-success fs-6">تسليم فوري</span>
                        @else
                            <span class="badge bg-info fs-6">حجز</span>
                            @if($order->delivery_date)
                                <p class="mt-2 mb-0"><strong>تاريخ التسليم:</strong> {{ $order->delivery_date }}</p>
                            @endif
                        @endif
                    </div>
                </div>
            </div>

            <!-- Customer Information -->
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5>معلومات العميل</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <p><strong>الاسم:</strong> {{ $order->customer_name }}</p>
                                <p><strong>الهاتف:</strong> {{ $order->customer_phone }}</p>
                                <p><strong>العنوان:</strong> {{ $order->delivery_address }}</p>
                            </div>
                            <div class="col-md-6">
                                <p><strong>نوع التوصيل:</strong>
                                    @if($order->delivery_type == 'male')
                                        رجالي
                                    @else
                                        نسائي
                                    @endif
                                </p>
                                <p><strong>طريقة الدفع:</strong>
                                    @if($order->payment_method == 'cash')
                                        كاش
                                    @else
                                        حوالة بنكية
                                    @endif
                                </p>
                                <p><strong>تاريخ الطلب:</strong> {{ $order->created_at->format('Y-m-d H:i') }}</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Order Items -->
                <div class="card mt-3">
                    <div class="card-header d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">المنتجات</h5>
                        @if($order->status == 'pending' || $order->status == 'confirmed')
                            <button type="button" class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addItemModal">
                                <i class="ti ti-plus"></i> إضافة منتج
                            </button>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="row g-3">
                            @foreach($order->items as $item)
                                <div class="col-12">
                                    <div class="card border shadow-sm h-100">
                                        <div class="card-body">
                                            <div class="d-flex justify-content-between align-items-start mb-3">
                                                <div class="flex-grow-1">
                                                    <h6 class="mb-1 fw-bold">{{ $item->product_name }}</h6>
                                                    @if($item->variant_combination)
                                                        @php
                                                            $variants = json_decode($item->variant_combination, true);
                                                        @endphp
                                                        @if($variants && is_array($variants))
                                                            <div class="mb-2">
                                                                @foreach($variants as $key => $value)
                                                                    <span class="badge bg-info me-1">{{ $key }}: {{ $value }}</span>
                                                                @endforeach
                                                            </div>
                                                        @endif
                                                    @endif
                                                </div>
                                                @if($order->status == 'pending' || $order->status == 'confirmed')
                                                    <form action="{{ route('admin.orders.items.remove', [$order->id, $item->id]) }}" method="POST" class="d-inline" onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟')">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="btn btn-sm btn-danger">
                                                            <i class="ti ti-trash"></i>
                                                        </button>
                                                    </form>
                                                @endif
                                            </div>

                                            <div class="row g-2">
                                                <div class="col-6 col-md-3">
                                                    <small class="text-muted d-block">السعر</small>
                                                    <strong>{{ number_format($item->product_price, 2) }} د.ل</strong>
                                                </div>

                                                <div class="col-6 col-md-3">
                                                    <small class="text-muted d-block">الكمية</small>
                                                    @if($order->status == 'pending' || $order->status == 'confirmed')
                                                        <form action="{{ route('admin.orders.items.update', [$order->id, $item->id]) }}" method="POST" class="d-inline-flex align-items-center gap-2">
                                                            @csrf
                                                            @method('PATCH')
                                                            <input type="number" name="quantity" value="{{ $item->quantity }}" min="1" class="form-control form-control-sm" style="width: 70px;">
                                                            <button type="submit" class="btn btn-sm btn-success">
                                                                <i class="ti ti-check"></i>
                                                            </button>
                                                        </form>
                                                    @else
                                                        <strong>{{ $item->quantity }}</strong>
                                                    @endif
                                                </div>

                                                <div class="col-12 col-md-6">
                                                    <small class="text-muted d-block">الإجمالي</small>
                                                    <h5 class="mb-0 text-primary">{{ number_format($item->subtotal, 2) }} د.ل</h5>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>

                <!-- Order Summary -->
                <div class="card mt-3">
                    <div class="card-header">
                        <h5>ملخص الطلب</h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-6 text-end">
                                <p><strong>المجموع الفرعي:</strong></p>
                                <p><strong>رسوم التوصيل:</strong></p>
                                @if($order->deposit_amount)
                                    <p><strong>العربون المطلوب:</strong></p>
                                @endif
                                <h5><strong>الإجمالي:</strong></h5>
                            </div>
                            <div class="col-6">
                                <p>{{ number_format($order->subtotal, 2) }} د.ل</p>
                                <p>{{ number_format($order->delivery_fee, 2) }} د.ل</p>
                                @if($order->deposit_amount)
                                    <p class="text-warning">{{ number_format($order->deposit_amount, 2) }} د.ل</p>
                                @endif
                                <h5 class="text-primary">{{ number_format($order->total, 2) }} د.ل</h5>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="mt-3">
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="ti ti-arrow-right"></i> العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

        <!-- Add Item Modal -->
        @if($order->status == 'pending' || $order->status == 'confirmed')
            <div class="modal fade" id="addItemModal" tabindex="-1" aria-labelledby="addItemModalLabel" aria-hidden="true">
                <div class="modal-dialog modal-lg">
                    <div class="modal-content">
                        <form action="{{ route('admin.orders.items.add', $order->id) }}" method="POST" id="addItemForm">
                            @csrf
                            <div class="modal-header">
                                <h5 class="modal-title" id="addItemModalLabel">إضافة منتج للطلب</h5>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body">
                                <!-- Product Selection -->
                                <div class="mb-3">
                                    <label class="form-label">المنتج <span class="text-danger">*</span></label>
                                    <select name="product_id" id="product_select" class="form-select" required onchange="loadProductVariants(this.value)">
                                        <option value="">اختر المنتج</option>
                                        @foreach($products as $product)
                                            <option value="{{ $product->id }}"
                                                    data-price="{{ $product->price }}"
                                                    data-has-variants="{{ $product->has_variants }}">
                                                {{ $product->name }} - {{ number_format($product->price, 2) }} د.ل
                                            </option>
                                        @endforeach
                                    </select>
                                </div>

                                <!-- Variants Section (Hidden by default) -->
                                <div id="variantsSection" style="display: none;">
                                    <div id="variantOptionsContainer"></div>
                                    <input type="hidden" name="variant_id" id="variant_id">
                                    <input type="hidden" name="variant_combination" id="variant_combination">
                                </div>

                                <!-- Quantity -->
                                <div class="mb-3">
                                    <label class="form-label">الكمية <span class="text-danger">*</span></label>
                                    <input type="number" name="quantity" class="form-control" min="1" value="1" required id="quantity_input">
                                </div>

                                <!-- Stock Info (for instant orders) -->
                                @if($order->order_type == 'instant')
                                    <div class="alert alert-info" id="stockInfo" style="display: none;">
                                        <i class="ti ti-info-circle"></i> <span id="stockMessage"></span>
                                    </div>
                                @endif
                            </div>
                            <div class="modal-footer">
                                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">إلغاء</button>
                                <button type="submit" class="btn btn-primary">
                                    <i class="ti ti-plus"></i> إضافة
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            @push('scripts')
            <script>
                // Product variants data
                const productsData = @json($products);

                function loadProductVariants(productId) {
                    const variantsSection = document.getElementById('variantsSection');
                    const variantOptionsContainer = document.getElementById('variantOptionsContainer');
                    const stockInfo = document.getElementById('stockInfo');

                    // Clear previous variants
                    variantOptionsContainer.innerHTML = '';
                    document.getElementById('variant_id').value = '';
                    document.getElementById('variant_combination').value = '';

                    if (!productId) {
                        variantsSection.style.display = 'none';
                        if (stockInfo) stockInfo.style.display = 'none';
                        return;
                    }

                    const product = productsData.find(p => p.id == productId);

                    if (product && product.has_variants && product.variants && product.variants.length > 0) {
                        variantsSection.style.display = 'block';

                        product.variants.forEach(variant => {
                            const variantDiv = document.createElement('div');
                            variantDiv.className = 'mb-3';
                            variantDiv.innerHTML = `
                                <label class="form-label">${variant.name} <span class="text-danger">*</span></label>
                                <select class="form-select variant-option" data-variant-name="${variant.name}" required>
                                    <option value="">اختر ${variant.name}</option>
                                    ${variant.options.map(opt => `<option value="${opt.option_value}">${opt.option_value}</option>`).join('')}
                                </select>
                            `;
                            variantOptionsContainer.appendChild(variantDiv);
                        });

                        // Add event listeners to all variant selects
                        document.querySelectorAll('.variant-option').forEach(select => {
                            select.addEventListener('change', updateVariantCombination);
                        });
                    } else {
                        variantsSection.style.display = 'none';
                        // Show stock for simple products
                        @if($order->order_type == 'instant')
                            updateStockInfo(product);
                        @endif
                    }
                }

                function updateVariantCombination() {
                    const allVariants = document.querySelectorAll('.variant-option');
                    let allSelected = true;
                    const combination = {};

                    allVariants.forEach(select => {
                        if (select.value) {
                            combination[select.dataset.variantName] = select.value;
                        } else {
                            allSelected = false;
                        }
                    });

                    if (allSelected) {
                        document.getElementById('variant_combination').value = JSON.stringify(combination);

                        @if($order->order_type == 'instant')
                            // Get product and show stock
                            const productId = document.getElementById('product_select').value;
                            const product = productsData.find(p => p.id == productId);
                            const combinationStr = JSON.stringify(combination);

                            // Find matching stock
                            if (product && product.stock) {
                                const matchingStock = product.stock.find(s =>
                                    s.variant_combination === combinationStr
                                );
                                updateStockInfo(product, matchingStock);
                            }
                        @endif
                    } else {
                        document.getElementById('variant_combination').value = '';
                    }
                }

                @if($order->order_type == 'instant')
                function updateStockInfo(product, stock = null) {
                    const stockInfo = document.getElementById('stockInfo');
                    const stockMessage = document.getElementById('stockMessage');

                    if (!stock && product && product.stock && product.stock.length > 0) {
                        // Simple product without variants
                        stock = product.stock.find(s => !s.product_variant_id);
                    }

                    if (stock) {
                        stockInfo.style.display = 'block';
                        const qty = stock.quantity || 0;
                        if (qty > 0) {
                            stockInfo.className = 'alert alert-success';
                            stockMessage.textContent = `المخزون المتوفر: ${qty}`;
                        } else {
                            stockInfo.className = 'alert alert-warning';
                            stockMessage.textContent = 'غير متوفر في المخزون';
                        }
                    } else {
                        stockInfo.style.display = 'none';
                    }
                }
                @endif
            </script>
            @endpush
        @endif
@endsection
