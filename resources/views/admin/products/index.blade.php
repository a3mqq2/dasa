@extends('layouts.app')

@section('title', 'المنتجات')

@push('styles')
<style>
    .product-card {
        transition: all 0.3s ease;
        border: 1px solid #e9ecef;
        height: 100%;
    }
    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 8px 20px rgba(0, 0, 0, 0.1);
    }
    .product-image {
        height: 200px;
        object-fit: cover;
        border-radius: 8px 8px 0 0;
    }
    .product-placeholder {
        height: 200px;
        background: linear-gradient(135deg, #f8f9fa 0%, #e9ecef 100%);
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 8px 8px 0 0;
    }
    .product-price {
        font-size: 1.25rem;
        font-weight: 700;
        color: #f5476b;
    }
    .variant-badge {
        font-size: 0.75rem;
        padding: 0.25rem 0.5rem;
    }
    @media (max-width: 768px) {
        .product-card {
            margin-bottom: 1rem;
        }
    }
</style>
@endpush

@section('content')
<div class="card mb-3">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">البحث والتصفية</h5>
            </div>
            <button class="btn btn-sm btn-light" type="button" data-bs-toggle="collapse" data-bs-target="#filterCollapse" aria-expanded="true" aria-controls="filterCollapse">
                <i class="ph-duotone ph-funnel me-1"></i>
                تصفية
            </button>
        </div>
    </div>
    <div class="collapse show" id="filterCollapse">
        <div class="card-body">
            <form action="{{ route('admin.products.index') }}" method="GET">
                <div class="row">
                    <div class="col-md-12">
                        <div class="input-group">
                            <span class="input-group-text">
                                <i class="ph-duotone ph-magnifying-glass"></i>
                            </span>
                            <input type="text"
                                   class="form-control"
                                   name="search"
                                   value="{{ request('search') }}"
                                   placeholder="ابحث عن منتج (الاسم، الوصف...)">
                            <button type="submit" class="btn btn-primary">
                                <i class="ph-duotone ph-magnifying-glass me-1"></i>
                                بحث
                            </button>
                            @if(request('search'))
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="ph-duotone ph-x me-1"></i>
                                    إعادة تعيين
                                </a>
                            @endif
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <div class="d-flex justify-content-between align-items-center">
            <div>
                <h5 class="mb-0">المنتجات</h5>
                <p class="text-muted mb-0 small">إدارة المنتجات والمخزون</p>
            </div>
            <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                <i class="ph-duotone ph-plus-circle me-2"></i>
                إضافة منتج جديد
            </a>
        </div>
    </div>
    <div class="card-body">
        <div class="row g-3">
    @forelse($products as $product)
        <div class="col-12 col-sm-6 col-md-4 col-lg-3">
            <div class="card product-card">
                @if($product->main_image)
                    <img src="{{ asset('storage/' . $product->main_image) }}"
                         alt="{{ $product->name }}"
                         class="product-image">
                @else
                    <div class="product-placeholder">
                        <i class="ph-duotone ph-image" style="font-size: 48px; opacity: 0.3;"></i>
                    </div>
                @endif

                <div class="card-body">
                    <h6 class="card-title mb-2 text-truncate" title="{{ $product->name }}">
                        {{ $product->name }}
                    </h6>

                    @if($product->description)
                        <p class="card-text text-muted small mb-2" style="
                            display: -webkit-box;
                            -webkit-line-clamp: 2;
                            -webkit-box-orient: vertical;
                            overflow: hidden;
                        ">
                            {{ $product->description }}
                        </p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center mb-2">
                        <span class="product-price">{{ number_format($product->price, 2) }} د.ل</span>
                        @if($product->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غير فعال</span>
                        @endif
                    </div>

                    @if($product->has_variants)
                        <div class="mb-2">
                            <span class="badge bg-info variant-badge">
                                <i class="ph-duotone ph-tag me-1"></i>
                                {{ $product->variants->count() }} متغيرات
                            </span>
                        </div>
                    @endif

                    @if($product->images->count() > 0)
                        <div class="mb-2">
                            <span class="badge bg-light text-dark variant-badge">
                                <i class="ph-duotone ph-images me-1"></i>
                                {{ $product->images->count() }} صور
                            </span>
                        </div>
                    @endif

                    <div class="mb-2">
                        <span class="badge {{ $product->total_stock > 0 ? 'bg-success' : 'bg-warning' }} variant-badge">
                            <i class="ph-duotone ph-package me-1"></i>
                            المخزون: {{ $product->total_stock }}
                        </span>
                    </div>

                    <div class="d-grid gap-2 mt-3">
                        <a href="{{ route('admin.products.show', $product->id) }}"
                           class="btn btn-sm btn-primary">
                            <i class="ph-duotone ph-eye me-1"></i>
                            عرض
                        </a>
                    </div>
                </div>

                <div class="card-footer text-muted small">
                    <i class="ph-duotone ph-calendar me-1"></i>
                    {{ $product->created_at->diffForHumans() }}
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="ph-duotone ph-package" style="font-size: 64px; opacity: 0.3;"></i>
                <h5 class="mt-3 text-muted">لا توجد منتجات</h5>
                <p class="text-muted">ابدأ بإضافة منتجك الأول</p>
            </div>
        </div>
    @endforelse
        </div>
    </div>

    @if($products->hasPages())
        <div class="card-footer">
            <div class="d-flex justify-content-center">
                {{ $products->links() }}
            </div>
        </div>
    @endif
</div>
@endsection
