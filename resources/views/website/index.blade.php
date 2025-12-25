@extends('layouts.website')

@section('title', 'الرئيسية - Dasa\'s Cake')

@push('styles')
<style>
    .track-order-link {
        position: fixed;
        top: 20px;
        left: 20px;
        z-index: 999;
        background: var(--primary-color);
        color: #fff;
        padding: 0.75rem 1.5rem;
        border-radius: 25px;
        text-decoration: none;
        font-weight: 600;
        font-size: 0.95rem;
        box-shadow: 0 4px 15px rgba(245, 71, 107, 0.3);
        transition: all 0.3s;
        display: inline-flex;
        align-items: center;
        gap: 0.5rem;
    }

    .track-order-link:hover {
        background: #e03858;
        color: #fff;
        transform: translateY(-2px);
        box-shadow: 0 6px 20px rgba(245, 71, 107, 0.4);
    }

    .track-order-link i {
        font-size: 1.2rem;
    }

    .welcome-text {
        text-align: center;
        margin: 2rem 0 3rem;
    }

    .welcome-text h1 {
        font-size: 2.5rem;
        font-weight: 700;
        color: var(--primary-color);
        margin-bottom: 0.5rem;
    }

    .welcome-text p {
        font-size: 1.1rem;
        color: #6c757d;
    }

    .choice-card {
        border: 3px solid var(--border-color);
        border-radius: 20px;
        padding: 3rem 2rem;
        text-align: center;
        transition: all 0.3s;
        height: 100%;
        background: #fff;
        cursor: pointer;
        text-decoration: none;
        display: block;
    }

    .choice-card:hover {
        border-color: var(--primary-color);
        transform: translateY(-10px);
        box-shadow: 0 15px 35px rgba(245, 71, 107, 0.15);
    }

    .choice-card .icon {
        font-size: 5rem;
        margin-bottom: 1.5rem;
        color: var(--primary-color);
    }

    .choice-card h3 {
        font-size: 1.5rem;
        font-weight: 600;
        margin-bottom: 1rem;
        color: var(--primary-color);
    }

    .choice-card p {
        color: #6c757d;
        margin-bottom: 1.5rem;
    }

    .choice-card .btn {
        min-width: 150px;
    }

    @media (max-width: 768px) {
        .track-order-link {
            top: 10px;
            left: 10px;
            padding: 0.6rem 1rem;
            font-size: 0.85rem;
        }

        .track-order-link i {
            font-size: 1rem;
        }
    }
</style>
@endpush

@section('content')
<!-- Track Order Link (Fixed Corner) -->
<a href="{{ route('order.track') }}" class="track-order-link">
    <i class="ph-duotone ph-package"></i>
    <span>متابعة طلبي</span>
</a>

<!-- Welcome Text -->
<div class="welcome-text">
    <h1>مرحباً بيك زبونا الكريم</h1>
    <p>اختر طريقة الطلب المناسبة لك</p>
</div>

<!-- Choice Cards -->
<div class="row g-4 justify-content-center">
    <!-- تسليم فوري -->
    <div class="col-md-6">
        <a href="{{ route('instant') }}" class="choice-card">
            <div class="icon">
                <img src="{{asset('fawre.png')}}" width="200" alt="">
            </div>
            <h3>تسليم فوري</h3>
            <p>اطلب الآن واستلم فوراً! منتجات متوفرة ومجهزة للتسليم الفوري</p>
            <span class="btn btn-primary">
                <i class="ph-duotone ph-arrow-left me-2"></i>
                تصفح المنتجات
            </span>
        </a>
    </div>

    <!-- أصناف بالحجز -->
    <div class="col-md-6">
        <a href="{{ route('reservation') }}" class="choice-card">
            <div class="icon">
                <img src="{{asset('delay.png')}}" width="250" alt="">
            </div>
            <h3>أصناف بالحجز</h3>
            <p>احجز طلبك مسبقاً واختر التاريخ المناسب للاستلام</p>
            <span class="btn btn-primary">
                <i class="ph-duotone ph-arrow-left me-2"></i>
                احجز الآن
            </span>
        </a>
    </div>
</div>

@endsection
