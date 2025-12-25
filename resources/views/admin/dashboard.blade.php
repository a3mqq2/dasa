@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="dashboard-container">
    <div class="row g-4 justify-content-center">
        <!-- Products Card -->
        <div class="col-md-6">
            <a href="{{ route('admin.products.index') }}" class="text-decoration-none">
                <div class="main-card products-card">
                    <div class="card-icon">
                        <i class="ti ti-box"></i>
                    </div>
                    <h3 class="card-title">المنتجات</h3>
                    <p class="card-description">إدارة المنتجات والمخزون</p>
                    <div class="card-stats">
                        <span class="stat-badge">{{ $stats['total_products'] }} منتج</span>
                        @if($stats['low_stock_products'] > 0)
                            <span class="stat-badge warning">{{ $stats['low_stock_products'] }} مخزون قليل</span>
                        @endif
                    </div>
                </div>
            </a>
        </div>

        <!-- Orders Card -->
        <div class="col-md-6">
            <a href="{{ route('admin.orders.index') }}" class="text-decoration-none">
                <div class="main-card orders-card">
                    <div class="card-icon">
                        <i class="ti ti-shopping-cart"></i>
                    </div>
                    <h3 class="card-title">الطلبات</h3>
                    <p class="card-description">عرض وإدارة الطلبات</p>
                    <div class="card-stats">
                        <span class="stat-badge">{{ $stats['total_orders'] }} طلب</span>
                        @if($stats['pending_orders'] > 0)
                            <span class="stat-badge warning">{{ $stats['pending_orders'] }} قيد الانتظار</span>
                        @endif
                    </div>
                </div>
            </a>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.dashboard-container {
    padding: 2rem 0;
    min-height: 60vh;
    display: flex;
    flex-direction: column;
    justify-content: center;
}

.welcome-section h2 {
    color: #2c3e50;
    font-weight: 700;
    font-size: 2rem;
}

.main-card {
    background: #fff;
    border: 2px solid #f0f0f0;
    border-radius: 20px;
    padding: 3rem 2rem;
    text-align: center;
    transition: all 0.3s ease;
    position: relative;
    overflow: hidden;
    min-height: 320px;
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
}

.main-card::before {
    content: '';
    position: absolute;
    top: 0;
    left: -100%;
    width: 100%;
    height: 100%;
    background: linear-gradient(90deg, transparent, rgba(139, 21, 56, 0.03), transparent);
    transition: left 0.5s ease;
}

.main-card:hover::before {
    left: 100%;
}

.main-card:hover {
    transform: translateY(-10px);
    box-shadow: 0 15px 40px rgba(0, 0, 0, 0.1);
    border-color: #f5476b;
}

.card-icon {
    width: 100px;
    height: 100px;
    background: linear-gradient(135deg, #f5476b 0%, #8b1538 100%);
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
    margin: 0 auto 2rem;
    transition: all 0.3s ease;
}

.main-card:hover .card-icon {
    transform: scale(1.1) rotate(5deg);
    box-shadow: 0 10px 30px rgba(245, 71, 107, 0.4);
}

.card-icon i {
    font-size: 3rem;
    color: #fff;
}

.card-title {
    font-size: 1.8rem;
    font-weight: 700;
    color: #2c3e50;
    margin-bottom: 0.75rem;
}

.card-description {
    color: #6c757d;
    font-size: 1rem;
    margin-bottom: 1.5rem;
}

.card-stats {
    display: flex;
    gap: 0.75rem;
    justify-content: center;
    flex-wrap: wrap;
}

.stat-badge {
    background: #e7f1ff;
    color: #0d6efd;
    padding: 0.5rem 1rem;
    border-radius: 20px;
    font-size: 0.9rem;
    font-weight: 600;
}

.stat-badge.warning {
    background: #fff3cd;
    color: #856404;
}

@media (max-width: 768px) {
    .dashboard-container {
        padding: 1rem 0;
    }

    .welcome-section h2 {
        font-size: 1.5rem;
    }

    .main-card {
        padding: 2rem 1.5rem;
        min-height: 280px;
    }

    .card-icon {
        width: 80px;
        height: 80px;
    }

    .card-icon i {
        font-size: 2.5rem;
    }

    .card-title {
        font-size: 1.5rem;
    }
}
</style>
@endpush
