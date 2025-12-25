@extends('layouts.app')

@section('title', 'عرض المنتج')

@push('styles')
<style>
    .product-main-image {
        width: 100%;
        max-height: 400px;
        object-fit: contain;
        border-radius: 8px;
        background: #f8f9fa;
    }
    .gallery-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(100px, 1fr));
        gap: 0.5rem;
        margin-top: 1rem;
    }
    .gallery-image {
        width: 100%;
        height: 100px;
        object-fit: cover;
        border-radius: 4px;
        cursor: pointer;
        transition: transform 0.2s;
    }
    .gallery-image:hover {
        transform: scale(1.05);
    }
    .info-label {
        font-weight: 600;
        color: #6c757d;
        margin-bottom: 0.5rem;
    }
    .info-value {
        font-size: 1.1rem;
        margin-bottom: 1rem;
    }
    .variant-item {
        background: #f8f9fa;
        padding: 1rem;
        border-radius: 8px;
        margin-bottom: 1rem;
    }
    .option-badge {
        display: inline-block;
        background: #e9ecef;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        margin: 0.25rem;
        font-size: 0.875rem;
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-lg-8">
        <div class="card">
            <div class="card-header">
                <div class="d-flex justify-content-between align-items-center">
                    <h5 class="mb-0">{{ $product->name }}</h5>
                    <div>
                        @if($product->is_active)
                            <span class="badge bg-success">فعال</span>
                        @else
                            <span class="badge bg-secondary">غير فعال</span>
                        @endif
                    </div>
                </div>
            </div>
            <div class="card-body">
                <!-- الصورة الرئيسية -->
                @if($product->main_image)
                    <div class="mb-4">
                        <img src="{{ asset('storage/' . $product->main_image) }}"
                             alt="{{ $product->name }}"
                             class="product-main-image">
                    </div>
                @else
                    <div class="text-center py-5 mb-4" style="background: #f8f9fa; border-radius: 8px;">
                        <i class="ph-duotone ph-image" style="font-size: 64px; opacity: 0.3;"></i>
                        <p class="text-muted mt-2">لا توجد صورة</p>
                    </div>
                @endif

                <!-- معرض الصور -->
                @if($product->images->count() > 0)
                    <div class="mb-4">
                        <h6 class="mb-3">صور إضافية ({{ $product->images->count() }})</h6>
                        <div class="gallery-images">
                            @foreach($product->images as $image)
                                <img src="{{ asset('storage/' . $image->image_path) }}"
                                     alt="Product Image"
                                     class="gallery-image"
                                     onclick="window.open('{{ asset('storage/' . $image->image_path) }}', '_blank')">
                            @endforeach
                        </div>
                    </div>
                @endif

                <!-- الوصف -->
                <div class="mb-4">
                    <div class="info-label">الوصف</div>
                    <div class="info-value">
                        {{ $product->description ?? 'لا يوجد وصف' }}
                    </div>
                </div>

                <!-- السعر -->
                <div class="mb-4">
                    <div class="info-label">السعر</div>
                    <div class="info-value text-primary" style="font-size: 1.5rem; font-weight: 700;">
                        {{ number_format($product->price, 2) }} د.ل
                    </div>
                </div>

                <!-- المتغيرات -->
                @if($product->has_variants && $product->variants->count() > 0)
                    <div class="mb-4">
                        <div class="info-label">المتغيرات ({{ $product->variants->count() }})</div>
                        @foreach($product->variants as $variant)
                            <div class="variant-item">
                                <h6 class="mb-2">{{ $variant->name }}</h6>
                                <div>
                                    @foreach($variant->options as $option)
                                        <span class="option-badge">{{ $option->value }}</span>
                                    @endforeach
                                </div>
                            </div>
                        @endforeach
                    </div>
                @endif
            </div>
        </div>
    </div>

    <div class="col-lg-4">
        <!-- Stock Management Card -->
        <div class="card mb-3">
            <div class="card-header">
                <h5 class="mb-0">
                    <i class="ph-duotone ph-package me-2"></i>
                    إدارة المخزون
                </h5>
            </div>
            <div class="card-body">
                <form action="{{ route('admin.products.stock.update', $product->id) }}" method="POST">
                    @csrf
                    @method('PUT')

                    @if(!$product->has_variants)
                        <!-- Simple quantity for products without variants -->
                        <div class="mb-3">
                            <label class="form-label">الكمية المتاحة</label>
                            <input type="number"
                                   class="form-control"
                                   name="quantity"
                                   value="{{ $product->stock->first()->quantity ?? 0 }}"
                                   min="0"
                                   required>
                            <small class="text-muted">إجمالي الكمية المتوفرة للتسليم الفوري</small>
                        </div>
                    @else
                        <!-- Quantity per variant option -->
                        @foreach($product->variants as $variant)
                            <div class="mb-3">
                                <label class="form-label fw-bold">{{ $variant->name }}</label>
                                @foreach($variant->options as $option)
                                    @php
                                        $stockEntry = $product->stock->where('product_variant_id', $variant->id)
                                                                     ->where('variant_combination', json_encode([$variant->name => $option->value]))
                                                                     ->first();
                                    @endphp
                                    <div class="input-group mb-2">
                                        <span class="input-group-text">{{ $option->value }}</span>
                                        <input type="number"
                                               class="form-control"
                                               name="stock[{{ $variant->id }}][{{ $option->id }}]"
                                               value="{{ $stockEntry->quantity ?? 0 }}"
                                               min="0"
                                               required>
                                    </div>
                                @endforeach
                            </div>
                        @endforeach
                    @endif

                    <div class="d-grid">
                        <button type="submit" class="btn btn-success">
                            <i class="ph-duotone ph-floppy-disk me-2"></i>
                            حفظ المخزون
                        </button>
                    </div>

                    @if($product->total_stock > 0)
                        <div class="alert alert-info mt-3 mb-0" style="font-size: 0.875rem;">
                            <i class="ph-duotone ph-info me-1"></i>
                            إجمالي المخزون: <strong>{{ $product->total_stock }}</strong>
                        </div>
                    @endif
                </form>
            </div>
        </div>

        <!-- Actions Card -->
        <div class="card">
            <div class="card-header">
                <h5 class="mb-0">الإجراءات</h5>
            </div>
            <div class="card-body">
                <div class="d-grid gap-2">
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                        <i class="ph-duotone ph-pencil me-2"></i>
                        تعديل المنتج
                    </a>

                    <form action="{{ route('admin.products.destroy', $product->id) }}"
                          method="POST"
                          onsubmit="return confirm('هل أنت متأكد من حذف هذا المنتج؟ سيتم حذف جميع الصور والمتغيرات المرتبطة به.')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger w-100">
                            <i class="ph-duotone ph-trash me-2"></i>
                            حذف المنتج
                        </button>
                    </form>

                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                        <i class="ph-duotone ph-arrow-left me-2"></i>
                        العودة للقائمة
                    </a>
                </div>
            </div>
        </div>

    </div>
</div>
@endsection
