@extends('layouts.website')

@section('title', 'تم إكمال الطلب - Dasa\'s Cake')

@push('styles')
<style>
    .order-complete-container {
        min-height: 60vh;
        display: flex;
        align-items: center;
        justify-content: center;
        padding: 2rem 0;
    }

    .complete-card {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        max-width: 600px;
        width: 100%;
    }

    .success-icon {
        width: 120px;
        height: 120px;
        margin: 0 auto 2rem;
    }

    .success-icon svg {
        width: 100%;
        height: 100%;
    }

    .complete-title {
        color: var(--primary-color);
        font-size: 2rem;
        font-weight: 700;
        margin-bottom: 1rem;
    }

    .complete-message {
        color: #6c757d;
        font-size: 1.1rem;
        margin-bottom: 2rem;
        line-height: 1.8;
    }

    .order-details {
        background: var(--light-bg);
        border-radius: 12px;
        padding: 1.5rem;
        margin: 2rem 0;
        text-align: right;
    }

    .order-detail-item {
        display: flex;
        justify-content: space-between;
        padding: 0.75rem 0;
        border-bottom: 1px dashed var(--border-color);
    }

    .order-detail-item:last-child {
        border-bottom: none;
    }

    .order-detail-label {
        color: #6c757d;
        font-weight: 600;
    }

    .order-detail-value {
        color: var(--secondary-color);
        font-weight: 700;
    }

    .order-number {
        font-size: 1.5rem;
        color: var(--primary-color);
    }

    .action-buttons {
        display: flex;
        gap: 1rem;
        flex-direction: column;
        margin-top: 2rem;
    }

    @media (min-width: 576px) {
        .action-buttons {
            flex-direction: row;
            justify-content: center;
        }
    }

    .btn-action {
        padding: 1rem 2rem;
        font-size: 1.1rem;
        border-radius: 10px;
        font-weight: 600;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 0.5rem;
    }

    .whatsapp-btn {
        background: #25D366;
        border-color: #25D366;
        color: #fff;
    }

    .whatsapp-btn:hover {
        background: #128C7E;
        border-color: #128C7E;
        color: #fff;
    }
</style>
@endpush

@section('content')
<div class="order-complete-container">
    <div class="complete-card">
        <!-- Success Icon -->
        <div class="success-icon">
            <img src="{{asset('complete.png')}}" style="width: 100%;" alt="">
        </div>

        <!-- Success Message -->
        <h1 class="complete-title">تم إكمال طلبك بنجاح!</h1>
        <p class="complete-message">
            شكراً لك! تم استلام طلبك وسيتم التواصل معك قريباً لتأكيد الطلب.
        </p>

        <!-- Order Details -->
        <div class="order-details">
            <div class="order-detail-item">
                <span class="order-detail-label">رقم الطلب</span>
                <span class="order-detail-value order-number">#{{ $order->id }}</span>
            </div>
            <div class="order-detail-item">
                <span class="order-detail-label">نوع الطلب</span>
                <span class="order-detail-value">
                    @if($order->order_type == 'instant')
                        تسليم فوري
                    @else
                        حجز
                    @endif
                </span>
            </div>
            @if($order->order_type == 'reservation' && $order->delivery_date)
                <div class="order-detail-item">
                    <span class="order-detail-label">تاريخ التسليم</span>
                    <span class="order-detail-value">{{ $order->delivery_date }}</span>
                </div>
            @endif
            <div class="order-detail-item">
                <span class="order-detail-label">الإجمالي</span>
                <span class="order-detail-value">{{ number_format($order->total, 2) }} د.ل</span>
            </div>
            @if($order->deposit_amount)
                <div class="order-detail-item">
                    <span class="order-detail-label">العربون المطلوب</span>
                    <span class="order-detail-value text-warning">{{ number_format($order->deposit_amount, 2) }} د.ل</span>
                </div>
            @endif
        </div>

        <!-- Important Note -->
        @if($order->payment_method == 'bank_transfer')
            <div class="alert alert-info text-right">
                <i class="ph-duotone ph-info me-2"></i>
                <strong>مهم:</strong> يرجى إرسال إيصال التحويل البنكي عبر واتساب للتأكيد
            </div>
        @endif

        <!-- Action Buttons -->
        <div class="action-buttons">
            <a href="https://wa.me/218910739550?text=مرحبا، رقم طلبي {{ $order->id }}"
               target="_blank"
               class="btn whatsapp-btn btn-action">
                <i class="ph-duotone ph-whatsapp-logo"></i>
                تواصل عبر واتساب
            </a>
            <a href="{{ route('order.track') }}" class="btn btn-secondary btn-action">
                <i class="ph-duotone ph-package"></i>
                متابعة الطلب
            </a>
        </div>

        <div class="mt-4">
            <a href="{{ route('home') }}" class="btn btn-outline-primary">
                <i class="ph-duotone ph-house me-2"></i>
                العودة للرئيسية
            </a>
        </div>
    </div>
</div>
@endsection
