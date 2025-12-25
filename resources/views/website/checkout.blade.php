@extends('layouts.website')

@section('title', 'إتمام الطلب - Dasa\'s Cake')

@push('styles')
<style>
    .checkout-section {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
    }

    .checkout-section h4 {
        color: var(--primary-color);
        margin-bottom: 1.25rem;
        padding-bottom: 0.75rem;
        border-bottom: 2px solid var(--border-color);
    }

    .bank-account {
        background: var(--light-bg);
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 0.75rem;
        display: flex;
        align-items: center;
        gap: 1rem;
    }

    .bank-icon {
        width: 48px;
        height: 48px;
        border: 4px solid var(--primary-color);
        color: #fff;
        border-radius: 50%;
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
    }

    .bank-details {
        flex: 1;
    }

    .bank-name {
        font-weight: 600;
        color: var(--primary-color);
        margin-bottom: 0.25rem;
    }

    .account-number {
        font-family: 'Courier New', monospace;
        font-size: 1.1rem;
        font-weight: 600;
        color: #333;
        direction: ltr;
        text-align: right;
    }

    .summary-item {
        display: flex;
        justify-content: space-between;
        padding: 0.5rem 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .summary-item:last-child {
        border-bottom: none;
        font-size: 1.25rem;
        font-weight: 700;
        color: var(--primary-color);
        padding-top: 1rem;
        margin-top: 0.5rem;
    }

    .deposit-highlight {
        background: linear-gradient(135deg, #fff3cd 0%, #ffe8a1 100%);
        border: 2px solid #ffc107;
        border-radius: 12px;
        padding: 1.25rem;
        margin: 1rem 0;
    }

    .deposit-highlight h5 {
        color: #856404;
        margin-bottom: 0.75rem;
    }

    .deposit-amount {
        font-size: 1.75rem;
        font-weight: 700;
        color: #856404;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <h1 class="mb-4">
            <i class="ph-duotone ph-shopping-bag me-2"></i>
            إتمام الطلب
        </h1>
    </div>
</div>

<form action="{{ route('order.store') }}" method="POST">
    @csrf

    <div class="row">
        <div class="col-lg-8">
            <!-- معلومات العميل -->
            <div class="checkout-section">
                <h4><i class="ph-duotone ph-user me-2"></i>معلومات العميل</h4>

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label class="form-label">الاسم الكامل <span class="text-danger">*</span></label>
                        <input type="text" name="customer_name" class="form-control @error('customer_name') is-invalid @enderror"
                               value="{{ old('customer_name') }}" required>
                        @error('customer_name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">رقم الهاتف <span class="text-danger">*</span></label>
                        <input type="tel" name="customer_phone" class="form-control @error('customer_phone') is-invalid @enderror"
                               value="{{ old('customer_phone') }}" required>
                        @error('customer_phone')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-12 mb-3">
                        <label class="form-label">عنوان التوصيل <span class="text-danger">*</span></label>
                        <textarea name="delivery_address" class="form-control @error('delivery_address') is-invalid @enderror"
                                  rows="3" required>{{ old('delivery_address') }}</textarea>
                        @error('delivery_address')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="col-md-6 mb-3">
                        <label class="form-label">نوع التوصيل <span class="text-danger">*</span></label>
                        <select name="delivery_type" class="form-select @error('delivery_type') is-invalid @enderror" required>
                            <option value="">اختر...</option>
                            <option value="male" {{ old('delivery_type') == 'male' ? 'selected' : '' }}>رجالي</option>
                            <option value="female" {{ old('delivery_type') == 'female' ? 'selected' : '' }}>نسائي</option>
                        </select>
                        @error('delivery_type')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($orderType === 'reservation')
                        <div class="col-md-6 mb-3">
                            <label class="form-label">تاريخ التسليم <span class="text-danger">*</span></label>
                            <input type="date" name="delivery_date" class="form-control @error('delivery_date') is-invalid @enderror"
                                   value="{{ old('delivery_date') }}" min="{{ date('Y-m-d', strtotime('+1 day')) }}" required>
                            @error('delivery_date')
                                <div class="invalid-feedback">{{ $message }}</div>
                            @enderror
                        </div>
                    @endif
                </div>
            </div>

            <!-- طريقة الدفع -->
            <div class="checkout-section">
                <h4><i class="ph-duotone ph-credit-card me-2"></i>طريقة الدفع</h4>

                <div class="mb-3">
                    <div class="form-check mb-2">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_cash"
                               value="cash" {{ old('payment_method', 'cash') == 'cash' ? 'checked' : '' }}
                               onchange="toggleBankAccounts()">
                        <label class="form-check-label" for="payment_cash">
                            <i class="ph-duotone ph-money me-2"></i>
                            <strong>نقداً عند الاستلام</strong>
                        </label>
                    </div>

                    <div class="form-check">
                        <input class="form-check-input" type="radio" name="payment_method" id="payment_bank"
                               value="bank_transfer" {{ old('payment_method') == 'bank_transfer' ? 'checked' : '' }}
                               onchange="toggleBankAccounts()">
                        <label class="form-check-label" for="payment_bank">
                            <i class="ph-duotone ph-bank me-2"></i>
                            <strong>حوالة بنكية</strong>
                        </label>
                    </div>
                </div>

                <!-- Bank Accounts (Hidden by default) -->
                <div id="bank_accounts" style="display: {{ old('payment_method') == 'bank_transfer' ? 'block' : 'none' }};">
                    <div class="alert alert-info">
                        <i class="ph-duotone ph-info me-2"></i>
                        يرجى التحويل إلى أحد الحسابات التالية وإرسال إيصال التحويل عبر واتساب
                    </div>

                    <div class="bank-account">
                        <div class="bank-icon">
                            <img src="{{asset('ncb.png')}}" width="30" alt="">
                        </div>
                        <div class="bank-details">
                            <div class="bank-name">المصرف التجاري</div>
                            <div class="account-number">308503498</div>
                        </div>
                    </div>

                    <div class="bank-account">
                        <div class="bank-icon">
                            <img src="{{asset('jum.jpeg')}}" width="30" alt="">
                        </div>
                        <div class="bank-details">
                            <div class="bank-name">مصرف الجمهورية</div>
                            <div class="account-number">1290293413</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <!-- ملخص الطلب -->
            <div class="checkout-section">
                <h4><i class="ph-duotone ph-receipt me-2"></i>ملخص الطلب</h4>

                <div class="mb-3">
                    @foreach($items as $item)
                        <div class="d-flex justify-content-between mb-2">
                            <div>
                                <div>{{ $item['product_name'] }}</div>
                                <small class="text-muted">{{ $item['quantity'] }} × {{ number_format($item['product_price'], 2) }}</small>
                            </div>
                            <strong>{{ number_format($item['product_price'] * $item['quantity'], 2) }} د.ل</strong>
                        </div>
                    @endforeach
                </div>

                <hr>

                <div class="summary-item">
                    <span>المجموع الفرعي</span>
                    <span>{{ number_format($subtotal, 2) }} د.ل</span>
                </div>

                <div class="summary-item">
                    <span>رسوم التوصيل</span>
                    <span>10.00 د.ل</span>
                </div>

                <div class="summary-item">
                    <span>الإجمالي</span>
                    <strong>{{ number_format($total, 2) }} د.ل</strong>
                </div>

                @if($needsDeposit)
                    <div class="deposit-highlight">
                        <h5>
                            <i class="ph-duotone ph-warning-circle me-2"></i>
                            عربون مطلوب
                        </h5>
                        <p class="mb-2">الطلب يتجاوز 1000 دينار، يجب دفع عربون:</p>
                        <div class="deposit-amount">{{ number_format($deposit, 2) }} د.ل</div>
                        <small class="text-muted">(50% من إجمالي الطلب)</small>
                    </div>
                @endif

                <button type="submit" class="btn btn-primary w-100 mt-3" style="padding: 0.75rem;">
                    <i class="ph-duotone ph-check-circle me-2"></i>
                    تأكيد الطلب
                </button>

                <a href="{{ route('cart.index') }}" class="btn btn-outline-secondary w-100 mt-2">
                    <i class="ph-duotone ph-arrow-right me-2"></i>
                    العودة للسلة
                </a>
            </div>
        </div>
    </div>
</form>

@push('scripts')
<script>
function toggleBankAccounts() {
    const bankTransfer = document.getElementById('payment_bank').checked;
    const bankAccounts = document.getElementById('bank_accounts');

    if (bankTransfer) {
        bankAccounts.style.display = 'block';
    } else {
        bankAccounts.style.display = 'none';
    }
}
</script>
@endpush
@endsection
