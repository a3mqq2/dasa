@extends('layouts.app')

@section('title', 'إدارة الطلبات')

@push('styles')
<style>
    .order-card {
        border: 2px solid #f0f0f0;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1rem;
        background: #fff;
        transition: all 0.3s;
    }

    .order-card:hover {
        border-color: #5b6b79;
        box-shadow: 0 4px 12px rgba(0,0,0,0.08);
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        margin-bottom: 1rem;
        padding-bottom: 1rem;
        border-bottom: 1px dashed #e0e0e0;
    }

    .order-number {
        font-size: 1.2rem;
        font-weight: 700;
        color: #5b6b79;
    }

    .order-info {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1rem;
        margin-bottom: 1rem;
    }

    .info-item {
        display: flex;
        flex-direction: column;
        gap: 0.25rem;
    }

    .info-label {
        font-size: 0.85rem;
        color: #6c757d;
    }

    .info-value {
        font-weight: 600;
        color: #2c3e50;
    }

    .order-actions {
        display: flex;
        gap: 0.5rem;
        justify-content: flex-end;
    }

    .filter-card {
        background: #fff;
        border-radius: 12px;
        padding: 1.5rem;
        margin-bottom: 1.5rem;
        border: 2px solid #f0f0f0;
    }

    .empty-state {
        text-align: center;
        padding: 3rem 1rem;
        color: #6c757d;
    }

    .empty-state i {
        font-size: 4rem;
        margin-bottom: 1rem;
        opacity: 0.3;
    }

    .dropdown-item-form {
        margin: 0;
        padding: 0;
    }

    .dropdown-item-form .dropdown-item {
        background: none;
        border: none;
        width: 100%;
        text-align: right;
        cursor: pointer;
        padding: 0.5rem 1rem;
    }

    .dropdown-item-form .dropdown-item:hover {
        background-color: #f8f9fa;
    }

    @media (max-width: 768px) {
        .order-info {
            grid-template-columns: 1fr;
        }

        .order-header {
            flex-direction: column;
            align-items: flex-start;
            gap: 0.5rem;
        }

        .order-actions {
            width: 100%;
            flex-wrap: wrap;
        }

        .order-actions > * {
            flex: 1;
            min-width: 120px;
        }
    }
</style>
@endpush

@section('content')
<div class="row">
    <div class="col-12">
        <!-- Back Button -->
        <div class="mb-3">
            <a href="{{ route('admin.dashboard') }}" class="btn btn-outline-secondary">
                <i class="ti ti-arrow-right me-2"></i>
                العودة للرئيسية
            </a>
        </div>

        <!-- Filters -->
        <div class="filter-card">
            <form method="GET" action="{{ route('admin.orders.index') }}" class="row g-3">
                <div class="col-md-4">
                    <label class="form-label">البحث</label>
                    <input type="text" name="search" class="form-control" value="{{ request('search') }}" placeholder="اسم أو رقم هاتف">
                </div>
                <div class="col-md-3">
                    <label class="form-label">الحالة</label>
                    <select name="status" class="form-select">
                        <option value="">الكل</option>
                        <option value="pending" {{ request('status') == 'pending' ? 'selected' : '' }}>قيد الانتظار</option>
                        <option value="confirmed" {{ request('status') == 'confirmed' ? 'selected' : '' }}>تمت الموافقة</option>
                        <option value="preparing" {{ request('status') == 'preparing' ? 'selected' : '' }}>قيد التحضير</option>
                        <option value="out_for_delivery" {{ request('status') == 'out_for_delivery' ? 'selected' : '' }}>للمندوب</option>
                        <option value="delivered" {{ request('status') == 'delivered' ? 'selected' : '' }}>تم التوصيل</option>
                        <option value="cancelled" {{ request('status') == 'cancelled' ? 'selected' : '' }}>ملغي</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">نوع الطلب</label>
                    <select name="order_type" class="form-select">
                        <option value="">الكل</option>
                        <option value="instant" {{ request('order_type') == 'instant' ? 'selected' : '' }}>فوري</option>
                        <option value="reservation" {{ request('order_type') == 'reservation' ? 'selected' : '' }}>حجز</option>
                    </select>
                </div>
                <div class="col-md-2 d-flex align-items-end gap-2">
                    <button type="submit" class="btn btn-primary flex-fill">
                        <i class="ti ti-search"></i> بحث
                    </button>
                    <a href="{{ route('admin.orders.index') }}" class="btn btn-secondary">
                        <i class="ti ti-x"></i>
                    </a>
                </div>
            </form>
        </div>

        <!-- Orders Cards -->
        @forelse($orders as $order)
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
                    'out_for_delivery' => 'للمندوب',
                    'delivered' => 'تم التوصيل',
                    'cancelled' => 'ملغي'
                ];
            @endphp

            <div class="order-card">
                <div class="order-header">
                    <span class="order-number">#{{ $order->id }}</span>
                    <span class="badge bg-{{ $statusColors[$order->status] }}">
                        {{ $statusLabels[$order->status] }}
                    </span>
                </div>

                <div class="order-info">
                    <div class="info-item">
                        <span class="info-label">العميل</span>
                        <span class="info-value">{{ $order->customer_name }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">الهاتف</span>
                        <span class="info-value">{{ $order->customer_phone }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">نوع الطلب</span>
                        <span class="info-value">
                            @if($order->order_type == 'instant')
                                <span class="badge bg-light-success">تسليم فوري</span>
                            @else
                                <span class="badge bg-light-info">حجز</span>
                            @endif
                        </span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">الإجمالي</span>
                        <span class="info-value text-success">{{ number_format($order->total, 2) }} د.ل</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">التاريخ</span>
                        <span class="info-value">{{ $order->created_at->format('Y-m-d') }}</span>
                    </div>
                    <div class="info-item">
                        <span class="info-label">الوقت</span>
                        <span class="info-value">{{ $order->created_at->format('H:i') }}</span>
                    </div>
                </div>

                <div class="order-actions">
                    <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                        <i class="ti ti-eye"></i> <span class="d-none d-md-inline">عرض</span>
                    </a>

                    <!-- Quick Status Change -->
                    @if($order->status != 'delivered' && $order->status != 'cancelled')
                        <div class="dropdown">
                            <button class="btn btn-sm btn-{{ $statusColors[$order->status] }} dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                <i class="ti ti-arrow-forward"></i> <span class="d-none d-md-inline">تغيير الحالة</span>
                            </button>
                            <ul class="dropdown-menu dropdown-menu-end">
                                @if($order->status == 'pending')
                                    <li>
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="dropdown-item-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="confirmed">
                                            <button type="submit" class="dropdown-item">
                                                <i class="ti ti-check-circle text-primary"></i> تمت الموافقة
                                            </button>
                                        </form>
                                    </li>
                                @endif

                                @if($order->status == 'confirmed')
                                    <li>
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="dropdown-item-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="preparing">
                                            <button type="submit" class="dropdown-item">
                                                <i class="ti ti-tools-kitchen text-info"></i> قيد التحضير
                                            </button>
                                        </form>
                                    </li>
                                @endif

                                @if($order->status == 'preparing')
                                    <li>
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="dropdown-item-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="out_for_delivery">
                                            <button type="submit" class="dropdown-item">
                                                <i class="ti ti-truck text-secondary"></i> في التوصيل
                                            </button>
                                        </form>
                                    </li>
                                @endif

                                @if($order->status == 'out_for_delivery')
                                    <li>
                                        <form action="{{ route('admin.orders.updateStatus', $order->id) }}" method="POST" class="dropdown-item-form">
                                            @csrf
                                            @method('PATCH')
                                            <input type="hidden" name="status" value="delivered">
                                            <button type="submit" class="dropdown-item">
                                                <i class="ti ti-circle-check text-success"></i> تم التوصيل
                                            </button>
                                        </form>
                                    </li>
                                @endif

                                <li><hr class="dropdown-divider"></li>
                                <li>
                                    <form action="{{ route('admin.orders.cancel', $order->id) }}" method="POST" class="dropdown-item-form" onsubmit="return confirm('هل أنت متأكد من إلغاء هذا الطلب؟')">
                                        @csrf
                                        <button type="submit" class="dropdown-item text-danger">
                                            <i class="ti ti-x"></i> إلغاء الطلب
                                        </button>
                                    </form>
                                </li>
                            </ul>
                        </div>
                    @endif
                </div>
            </div>
        @empty
            <div class="empty-state">
                <i class="ph-duotone ph-shopping-cart"></i>
                <h4>لا توجد طلبات</h4>
                <p>لم يتم العثور على أي طلبات بالمعايير المحددة</p>
            </div>
        @endforelse

        <!-- Pagination -->
        @if($orders->hasPages())
            <div class="mt-3">
                {{ $orders->links() }}
            </div>
        @endif
    </div>
</div>
@endsection
