@extends('layouts.website')

@section('title', 'الحجز - Dasa\'s Cake')

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

    .filter-section {
        background: #fff;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        padding: 1.25rem;
        margin-bottom: 2rem;
    }

    .filter-section label {
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .product-card {
        position: relative;
        border: 1px solid var(--border-color);
        border-radius: 12px;
        overflow: hidden;
        transition: all 0.3s ease;
        height: 100%;
        cursor: pointer;
        background: #fff;
    }

    .product-card:hover {
        transform: translateY(-5px);
        box-shadow: 0 10px 25px rgba(245, 71, 107, 0.15);
        border-color: var(--primary-color);
    }

    .reservation-badge {
        position: absolute;
        top: 15px;
        left: 15px;
        background: var(--secondary-color);
        color: #fff;
        padding: 0.4rem 1rem;
        border-radius: 20px;
        font-size: 0.85rem;
        font-weight: 600;
        z-index: 10;
    }

    .product-image-wrapper {
        position: relative;
        width: 100%;
        height: 300px;
        overflow: hidden;
    }

    .product-image {
        width: 100%;
        height: 100%;
        object-fit: cover;
        transition: transform 0.3s ease;
    }

    .product-card:hover .product-image {
        transform: scale(1.05);
    }

    .product-placeholder {
        width: 100%;
        height: 300px;
        background: var(--light-bg);
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .product-info {
        padding: 1.5rem;
    }

    .product-name {
        font-size: 1.25rem;
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.75rem;
        min-height: 60px;
    }

    .product-description {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 1rem;
        min-height: 45px;
    }

    .price-tag {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
        display: flex;
        align-items: center;
        gap: 0.5rem;
    }

    .view-details-btn {
        margin-top: 1rem;
        width: 100%;
        padding: 0.75rem;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 8px;
        font-weight: 600;
        transition: all 0.2s;
    }

    .view-details-btn:hover {
        background: #e03858;
        transform: translateY(-2px);
    }

    /* Modal Styles */
    .modal-header {
        background: var(--light-bg);
        border-bottom: 2px solid var(--border-color);
    }

    .modal-title {
        color: var(--primary-color);
        font-weight: 700;
    }

    .modal-product-image {
        width: 100%;
        height: 400px;
        object-fit: cover;
        border-radius: 12px;
    }

    .modal-product-placeholder {
        width: 100%;
        height: 400px;
        background: var(--light-bg);
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
    }

    .image-gallery {
        position: relative;
        margin-bottom: 1rem;
    }

    .gallery-thumbnails {
        display: flex;
        gap: 0.5rem;
        margin-top: 0.75rem;
        overflow-x: auto;
        padding: 0.25rem;
    }

    .gallery-thumbnail {
        width: 80px;
        height: 80px;
        object-fit: cover;
        border-radius: 8px;
        cursor: pointer;
        border: 2px solid transparent;
        transition: all 0.3s;
        flex-shrink: 0;
    }

    .gallery-thumbnail:hover {
        border-color: var(--primary-color);
        transform: scale(1.05);
    }

    .gallery-thumbnail.active {
        border-color: var(--primary-color);
        box-shadow: 0 0 0 2px rgba(245, 71, 107, 0.2);
    }

    .modal-price {
        font-size: 2rem;
        font-weight: 700;
        color: var(--primary-color);
        margin: 1rem 0;
    }

    .modal-description {
        color: #495057;
        line-height: 1.8;
        margin-bottom: 1.5rem;
    }

    .variant-section {
        background: var(--light-bg);
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }

    .variant-section label {
        font-weight: 600;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .add-to-cart-modal-btn {
        padding: 1rem;
        font-size: 1.1rem;
        font-weight: 600;
        background: var(--primary-color);
        border-color: var(--primary-color);
    }

    .add-to-cart-modal-btn:hover {
        background: #e03858;
        border-color: #e03858;
    }
</style>
@endpush

@section('content')
<div class="page-header">
    <h1><i class="ph-duotone ph-calendar-check me-2"></i>أصناف بالحجز</h1>
</div>

<!-- Filter Section -->
<div class="filter-section">
    <div class="row align-items-end">
        <div class="col-md-6 col-lg-4">
            <label for="sortFilter">
                <i class="ph-duotone ph-funnel me-1"></i>
                ترتيب حسب
            </label>
            <select id="sortFilter" class="form-select" onchange="applySort(this.value)">
                <option value="desc" {{ request('sort') == 'desc' || !request('sort') ? 'selected' : '' }}>
                    الأحدث أولاً
                </option>
                <option value="asc" {{ request('sort') == 'asc' ? 'selected' : '' }}>
                    الأقدم أولاً
                </option>
                <option value="price_asc" {{ request('sort') == 'price_asc' ? 'selected' : '' }}>
                    السعر: من الأقل للأعلى
                </option>
                <option value="price_desc" {{ request('sort') == 'price_desc' ? 'selected' : '' }}>
                    السعر: من الأعلى للأقل
                </option>
            </select>
        </div>
    </div>
</div>

<!-- Products Grid -->
<div class="row g-4">
    @forelse($products as $product)
        <div class="col-lg-6">
            <div class="product-card" onclick="openProductModal({{ $product->id }})">
                <span class="reservation-badge">
                    <i class="ph-duotone ph-calendar-check me-1"></i>
                    حجز
                </span>

                <div class="product-image-wrapper">
                    @if($product->main_image)
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->name }}"
                             class="product-image">
                    @else
                        <div class="product-placeholder">
                            <i class="ph-duotone ph-image" style="font-size: 80px; opacity: 0.3;"></i>
                        </div>
                    @endif
                </div>

                <div class="product-info">
                    <h5 class="product-name">{{ $product->name }}</h5>

                    @if($product->description)
                        <p class="product-description">{{ Str::limit($product->description, 100) }}</p>
                    @endif

                    <div class="d-flex justify-content-between align-items-center">
                        <div class="price-tag">
                            <i class="ph-duotone ph-currency-circle-dollar"></i>
                            {{ number_format($product->price, 2) }} د.ل
                        </div>
                    </div>

                    <button class="view-details-btn" onclick="event.stopPropagation(); openProductModal({{ $product->id }})">
                        <i class="ph-duotone ph-eye me-2"></i>
                        عرض التفاصيل
                    </button>
                </div>
            </div>
        </div>

        <!-- Product Modal -->
        <div class="modal fade" id="productModal{{ $product->id }}" tabindex="-1" aria-hidden="true">
            <div class="modal-dialog modal-lg modal-dialog-centered">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title">
                            <i class="ph-duotone ph-package me-2"></i>
                            {{ $product->name }}
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col-md-6">
                                <div class="image-gallery">
                                    @php
                                        $allImages = collect();
                                        if($product->main_image) {
                                            $allImages->push($product->main_image);
                                        }
                                        if($product->images && $product->images->count() > 0) {
                                            $allImages = $allImages->merge($product->images->pluck('image_path'));
                                        }
                                    @endphp

                                    @if($allImages->count() > 0)
                                        <!-- Main Image -->
                                        <img src="{{ asset('storage/' . $allImages->first()) }}"
                                             alt="{{ $product->name }}"
                                             class="modal-product-image"
                                             id="mainImage{{ $product->id }}">

                                        <!-- Thumbnails -->
                                        @if($allImages->count() > 1)
                                            <div class="gallery-thumbnails">
                                                @foreach($allImages as $index => $image)
                                                    <img src="{{ asset('storage/' . $image) }}"
                                                         alt="{{ $product->name }}"
                                                         class="gallery-thumbnail {{ $index === 0 ? 'active' : '' }}"
                                                         onclick="changeImage{{ $product->id }}('{{ asset('storage/' . $image) }}', this)">
                                                @endforeach
                                            </div>
                                        @endif
                                    @else
                                        <div class="modal-product-placeholder">
                                            <i class="ph-duotone ph-image" style="font-size: 100px; opacity: 0.3;"></i>
                                        </div>
                                    @endif
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="modal-price">{{ number_format($product->price, 2) }} د.ل</div>

                                @if($product->description)
                                    <div class="modal-description">{{ $product->description }}</div>
                                @endif

                                <form action="{{ route('cart.add') }}" method="POST">
                                    @csrf
                                    <input type="hidden" name="product_id" value="{{ $product->id }}">
                                    <input type="hidden" name="order_type" value="reservation">

                                    @if($product->has_variants && $product->variants->count() > 0)
                                        <div class="variant-section">
                                            <h6 class="mb-3">
                                                <i class="ph-duotone ph-list-bullets me-2"></i>
                                                اختر المتغيرات
                                            </h6>
                                            @foreach($product->variants as $variant)
                                                <div class="mb-3">
                                                    <label class="form-label">{{ $variant->name }}</label>
                                                    <select name="variant_id" class="form-select" required>
                                                        <option value="">اختر...</option>
                                                        @foreach($variant->options as $option)
                                                            <option value="{{ $variant->id }}"
                                                                    data-combination="{{ json_encode([$variant->name => $option->value]) }}">
                                                                {{ $option->value }}
                                                            </option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            @endforeach
                                            <input type="hidden" name="variant_combination" id="modal_variant_combination_{{ $product->id }}">
                                        </div>
                                    @endif

                                    <div class="mb-3">
                                        <label class="form-label fw-bold">
                                            <i class="ph-duotone ph-hash me-1"></i>
                                            الكمية
                                        </label>
                                        <input type="number"
                                               name="quantity"
                                               class="form-control mb-3 form-control-lg"
                                               value="1"
                                               min="1"
                                               required>
                                    </div>

                                    <button type="submit" class="btn btn-primary mt-3 add-to-cart-modal-btn w-100">
                                        <i class="ph-duotone ph-shopping-cart me-2"></i>
                                        أضف للسلة
                                    </button>
                                </form>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @empty
        <div class="col-12">
            <div class="text-center py-5">
                <i class="ph-duotone ph-package" style="font-size: 64px; opacity: 0.3; color: var(--primary-color);"></i>
                <h4 class="mt-3 text-muted">لا توجد منتجات حالياً</h4>
                <p class="text-muted">تحقق لاحقاً من المنتجات الجديدة</p>
                <a href="{{ route('home') }}" class="btn btn-primary mt-2">
                    العودة للرئيسية
                </a>
            </div>
        </div>
    @endforelse
</div>

@if($products->hasPages())
    <div class="mt-4 d-flex justify-content-center">
        {{ $products->links() }}
    </div>
@endif

@push('scripts')
<script>
// Sort filter
function applySort(sortValue) {
    const url = new URL(window.location.href);
    url.searchParams.set('sort', sortValue);
    window.location.href = url.toString();
}

// Open product modal
function openProductModal(productId) {
    const modal = new bootstrap.Modal(document.getElementById('productModal' + productId));
    modal.show();
}

// Update variant combination when select changes in modals
document.querySelectorAll('select[name="variant_id"]').forEach(select => {
    select.addEventListener('change', function() {
        const form = this.closest('form');
        const productId = form.querySelector('[name="product_id"]').value;
        const combinationInput = document.getElementById('modal_variant_combination_' + productId);
        const selectedOption = this.options[this.selectedIndex];
        const combination = selectedOption.getAttribute('data-combination');
        if (combinationInput && combination) {
            combinationInput.value = combination;
        }
    });
});

// Image gallery functions - Create dynamic functions for each product
@foreach($products as $product)
window.changeImage{{ $product->id }} = function(imageSrc, thumbnail) {
    // Update main image
    const mainImage = document.getElementById('mainImage{{ $product->id }}');
    if (mainImage) {
        mainImage.src = imageSrc;
    }

    // Update active thumbnail
    const modal = document.getElementById('productModal{{ $product->id }}');
    if (modal) {
        modal.querySelectorAll('.gallery-thumbnail').forEach(thumb => {
            thumb.classList.remove('active');
        });
        thumbnail.classList.add('active');
    }
};
@endforeach
</script>
@endpush
@endsection
