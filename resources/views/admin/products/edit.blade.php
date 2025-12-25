@extends('layouts.app')

@section('title', 'تعديل منتج')

@push('styles')
<style>
    .custom-file-upload {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 2rem;
        text-align: center;
        cursor: pointer;
        transition: all 0.3s;
        background: #f8f9fa;
    }
    .custom-file-upload:hover {
        border-color: #4680ff;
        background: #f0f4ff;
    }
    .custom-file-upload input[type="file"] {
        display: none;
    }
    .preview-container {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
        margin-top: 1rem;
    }
    .preview-image {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    .preview-image img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    .existing-images {
        display: grid;
        grid-template-columns: repeat(auto-fill, minmax(150px, 1fr));
        gap: 1rem;
    }
    .image-wrapper {
        position: relative;
        border-radius: 8px;
        overflow: hidden;
    }
    .image-wrapper img {
        width: 100%;
        height: 150px;
        object-fit: cover;
    }
    .remove-image-btn {
        position: absolute;
        top: 5px;
        right: 5px;
        background: #dc3545;
        color: white;
        border: none;
        border-radius: 50%;
        width: 30px;
        height: 30px;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        opacity: 0.9;
        transition: opacity 0.3s;
    }
    .remove-image-btn:hover {
        opacity: 1;
    }
    .variant-card {
        border: 2px dashed #dee2e6;
        border-radius: 8px;
        padding: 1rem;
        margin-bottom: 1rem;
        background: #f8f9fa;
    }
    .option-tag {
        display: inline-block;
        background: #e9ecef;
        padding: 0.25rem 0.75rem;
        border-radius: 20px;
        margin: 0.25rem;
        position: relative;
    }
    .option-tag .remove-option {
        margin-left: 0.5rem;
        cursor: pointer;
        color: #dc3545;
    }
</style>
@endpush

@section('content')
<form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
    @csrf
    @method('PUT')

    <div class="row">
        <div class="col-lg-8">
            <div class="card">
                <div class="card-header">
                    <h5>البيانات الأساسية</h5>
                </div>
                <div class="card-body">
                    <div class="mb-3">
                        <label for="name" class="form-label">اسم المنتج <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror"
                               id="name" name="name" value="{{ old('name', $product->name) }}" required>
                        @error('name')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="description" class="form-label">وصف المنتج</label>
                        <textarea class="form-control @error('description') is-invalid @enderror"
                                  id="description" name="description" rows="4">{{ old('description', $product->description) }}</textarea>
                        @error('description')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label for="price" class="form-label">السعر <span class="text-danger">*</span></label>
                        <div class="input-group">
                            <input type="number" step="0.01" class="form-control @error('price') is-invalid @enderror"
                                   id="price" name="price" value="{{ old('price', $product->price) }}" required>
                            <span class="input-group-text">د.ل</span>
                        </div>
                        @error('price')
                            <div class="invalid-feedback">{{ $message }}</div>
                        @enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">الصورة الأساسية الحالية</label>
                        @if($product->main_image)
                            <div class="mb-2">
                                <img src="{{ asset('storage/' . $product->main_image) }}"
                                     alt="{{ $product->name }}"
                                     style="max-width: 200px; border-radius: 8px;">
                            </div>
                        @endif
                        <label class="form-label">تحديث الصورة الأساسية</label>
                        <label class="custom-file-upload">
                            <input type="file" name="main_image" accept="image/*" onchange="previewMainImage(this)">
                            <div>
                                <i class="ph-duotone ph-upload" style="font-size: 48px; color: #4680ff;"></i>
                                <p class="mb-0 mt-2">اضغط لاختيار صورة جديدة</p>
                                <small class="text-muted">الحد الأقصى: 2MB</small>
                            </div>
                        </label>
                        <div id="main_image_preview" class="preview-container"></div>
                        @error('main_image')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>

                    @if($product->images->count() > 0)
                        <div class="mb-3">
                            <label class="form-label">الصور الإضافية الحالية</label>
                            <div class="existing-images">
                                @foreach($product->images as $image)
                                    <div class="image-wrapper" id="existing_image_{{ $image->id }}">
                                        <img src="{{ asset('storage/' . $image->image_path) }}" alt="Product Image">
                                        <button type="button" class="remove-image-btn"
                                                onclick="deleteImage({{ $image->id }})">
                                            <i class="ph-duotone ph-x"></i>
                                        </button>
                                    </div>
                                @endforeach
                            </div>
                        </div>
                    @endif

                    <div class="mb-3">
                        <label class="form-label">إضافة صور جديدة (Gallery)</label>
                        <label class="custom-file-upload">
                            <input type="file" name="gallery_images[]" accept="image/*" multiple onchange="previewGalleryImages(this)">
                            <div>
                                <i class="ph-duotone ph-images" style="font-size: 48px; color: #4680ff;"></i>
                                <p class="mb-0 mt-2">اضغط لاختيار صور إضافية</p>
                                <small class="text-muted">يمكنك اختيار عدة صور - الحد الأقصى لكل صورة: 2MB</small>
                            </div>
                        </label>
                        <div id="gallery_images_preview" class="preview-container"></div>
                        @error('gallery_images.*')
                            <div class="text-danger small">{{ $message }}</div>
                        @enderror
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h5>المتغيرات (Variants)</h5>
                    <div class="form-check form-switch">
                        <input type="hidden" name="has_variants" value="0">
                        <input class="form-check-input" type="checkbox" id="has_variants"
                               name="has_variants" value="1" {{ $product->has_variants ? 'checked' : '' }}
                               onchange="toggleVariants(this)">
                        <label class="form-check-label" for="has_variants">
                            هل للمنتج متغيرات؟
                        </label>
                    </div>
                </div>
                <div class="card-body" id="variants_container" style="display: {{ $product->has_variants ? 'block' : 'none' }};">
                    <div id="variants_list">
                        @foreach($product->variants as $index => $variant)
                            <div class="variant-card" id="variant_{{ $index }}">
                                <div class="d-flex justify-content-between align-items-center mb-3">
                                    <h6 class="mb-0">متغير {{ $index + 1 }}</h6>
                                    <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant({{ $index }})">
                                        <i class="ph-duotone ph-trash"></i>
                                    </button>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">اسم المتغير</label>
                                    <input type="text" class="form-control" name="variants[{{ $index }}][name]"
                                           value="{{ $variant->name }}" required>
                                </div>

                                <div class="mb-3">
                                    <label class="form-label">خيارات المتغير</label>
                                    <div class="input-group mb-2">
                                        <input type="text" class="form-control" id="option_input_{{ $index }}"
                                               placeholder="إضافة خيار جديد">
                                        <button type="button" class="btn btn-primary" onclick="addOption({{ $index }})">
                                            إضافة
                                        </button>
                                    </div>
                                    <div id="options_container_{{ $index }}">
                                        @foreach($variant->options as $option)
                                            <span class="option-tag">
                                                {{ $option->value }}
                                                <input type="hidden" name="variants[{{ $index }}][options][]" value="{{ $option->value }}">
                                                <i class="ph-duotone ph-x remove-option" onclick="this.parentElement.remove()"></i>
                                            </span>
                                        @endforeach
                                    </div>
                                </div>
                            </div>
                        @endforeach
                    </div>
                    <button type="button" class="btn btn-sm btn-outline-primary" onclick="addVariant()">
                        <i class="ph-duotone ph-plus me-1"></i>
                        إضافة متغير
                    </button>
                </div>
            </div>
        </div>

        <div class="col-lg-4">
            <div class="card">
                <div class="card-header">
                    <h5>الإعدادات</h5>
                </div>
                <div class="card-body">
                    <div class="form-check form-switch mb-3">
                        <input type="hidden" name="is_active" value="0">
                        <input class="form-check-input" type="checkbox" id="is_active"
                               name="is_active" value="1" {{ $product->is_active ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">
                            فعال
                        </label>
                    </div>
                </div>
            </div>

            <div class="card mt-3">
                <div class="card-body">
                    <button type="submit" class="btn btn-primary w-100 mb-2">
                        <i class="ph-duotone ph-floppy-disk me-2"></i>
                        تحديث المنتج
                    </button>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-secondary w-100">
                        <i class="ph-duotone ph-x me-2"></i>
                        إلغاء
                    </a>
                </div>
            </div>
        </div>
    </div>
</form>
@endsection

@push('scripts')
<script>
function previewMainImage(input) {
    const preview = document.getElementById('main_image_preview');
    preview.innerHTML = '';

    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            preview.innerHTML = `
                <div class="preview-image">
                    <img src="${e.target.result}" alt="Preview">
                </div>
            `;
        };
        reader.readAsDataURL(input.files[0]);
    }
}

function previewGalleryImages(input) {
    const preview = document.getElementById('gallery_images_preview');
    preview.innerHTML = '';

    if (input.files) {
        Array.from(input.files).forEach(file => {
            const reader = new FileReader();
            reader.onload = function(e) {
                preview.innerHTML += `
                    <div class="preview-image">
                        <img src="${e.target.result}" alt="Preview">
                    </div>
                `;
            };
            reader.readAsDataURL(file);
        });
    }
}

let variantIndex = {{ $product->variants->count() }};

function toggleVariants(checkbox) {
    const container = document.getElementById('variants_container');
    container.style.display = checkbox.checked ? 'block' : 'none';
}

function addVariant() {
    const variantsList = document.getElementById('variants_list');
    const variantHtml = `
        <div class="variant-card" id="variant_${variantIndex}">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h6 class="mb-0">متغير ${variantIndex + 1}</h6>
                <button type="button" class="btn btn-sm btn-danger" onclick="removeVariant(${variantIndex})">
                    <i class="ph-duotone ph-trash"></i>
                </button>
            </div>

            <div class="mb-3">
                <label class="form-label">اسم المتغير</label>
                <input type="text" class="form-control" name="variants[${variantIndex}][name]"
                       placeholder="مثال: الحشوة" required>
            </div>

            <div class="mb-3">
                <label class="form-label">خيارات المتغير</label>
                <div class="input-group mb-2">
                    <input type="text" class="form-control" id="option_input_${variantIndex}"
                           placeholder="مثال: فستق، ليمون، شوكولاتة">
                    <button type="button" class="btn btn-primary" onclick="addOption(${variantIndex})">
                        إضافة
                    </button>
                </div>
                <div id="options_container_${variantIndex}"></div>
            </div>
        </div>
    `;

    variantsList.insertAdjacentHTML('beforeend', variantHtml);
    variantIndex++;
}

function removeVariant(index) {
    const variant = document.getElementById(`variant_${index}`);
    if (variant && confirm('هل أنت متأكد من حذف هذا المتغير؟')) {
        variant.remove();
    }
}

function addOption(variantIndex) {
    const input = document.getElementById(`option_input_${variantIndex}`);
    const value = input.value.trim();

    if (!value) return;

    const container = document.getElementById(`options_container_${variantIndex}`);

    const optionHtml = `
        <span class="option-tag">
            ${value}
            <input type="hidden" name="variants[${variantIndex}][options][]" value="${value}">
            <i class="ph-duotone ph-x remove-option" onclick="this.parentElement.remove()"></i>
        </span>
    `;

    container.insertAdjacentHTML('beforeend', optionHtml);
    input.value = '';
}

function deleteImage(imageId) {
    if (!confirm('هل أنت متأكد من حذف هذه الصورة؟')) {
        return;
    }

    fetch(`/admin/products/images/${imageId}`, {
        method: 'DELETE',
        headers: {
            'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
            'Content-Type': 'application/json',
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success) {
            document.getElementById(`existing_image_${imageId}`).remove();
        } else {
            alert('حدث خطأ أثناء حذف الصورة');
        }
    })
    .catch(error => {
        console.error('Error:', error);
        alert('حدث خطأ أثناء حذف الصورة');
    });
}
</script>
@endpush
