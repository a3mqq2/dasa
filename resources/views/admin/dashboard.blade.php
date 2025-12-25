@extends('layouts.app')

@section('title', 'لوحة التحكم')

@section('content')
<div class="">
    <!-- Revenue Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">مبيعات اليوم</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['today_revenue'], 2) }} د.ل</h3>
                            <small class="text-muted">{{ $stats['today_orders'] }} طلب</small>
                        </div>
                        <div class="icon-circle soft-success">
                            <i class="ti ti-cash fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">مبيعات الشهر</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['month_revenue'], 2) }} د.ل</h3>
                            <small class="text-muted">{{ now()->format('F') }}</small>
                        </div>
                        <div class="icon-circle soft-primary">
                            <i class="ti ti-calendar-month fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-4">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">إجمالي المبيعات</p>
                            <h3 class="mb-0 fw-bold">{{ number_format($stats['total_revenue'], 2) }} د.ل</h3>
                            <small class="text-muted">{{ $stats['total_orders'] }} طلب</small>
                        </div>
                        <div class="icon-circle soft-info">
                            <i class="ti ti-chart-line fs-2"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Orders Status Cards -->
    <div class="row g-3 mb-4">
        <div class="col-md-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">قيد الانتظار</p>
                            <h4 class="mb-0 fw-bold text-warning">{{ $stats['pending_orders'] }}</h4>
                        </div>
                        <div class="icon-circle" style="background: #fff3cd; color: #ffc107;">
                            <i class="ti ti-clock fs-3"></i>
                        </div>
                    </div>
                    @if($stats['pending_orders'] > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'pending']) }}" class="btn btn-sm btn-warning w-100 mt-3">
                            <i class="ti ti-eye"></i> عرض
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">تمت الموافقة</p>
                            <h4 class="mb-0 fw-bold text-primary">{{ $stats['confirmed_orders'] }}</h4>
                        </div>
                        <div class="icon-circle soft-primary">
                            <i class="ti ti-check-circle fs-3"></i>
                        </div>
                    </div>
                    @if($stats['confirmed_orders'] > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'confirmed']) }}" class="btn btn-sm btn-primary w-100 mt-3">
                            <i class="ti ti-eye"></i> عرض
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">قيد التحضير</p>
                            <h4 class="mb-0 fw-bold text-info">{{ $stats['preparing_orders'] }}</h4>
                        </div>
                        <div class="icon-circle soft-info">
                            <i class="ti ti-tools-kitchen fs-3"></i>
                        </div>
                    </div>
                    @if($stats['preparing_orders'] > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'preparing']) }}" class="btn btn-sm btn-info w-100 mt-3">
                            <i class="ti ti-eye"></i> عرض
                        </a>
                    @endif
                </div>
            </div>
        </div>

        <div class="col-md-3 col-sm-6">
            <div class="card stat-card border-0 shadow-sm h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1 small">في التوصيل</p>
                            <h4 class="mb-0 fw-bold text-secondary">{{ $stats['out_for_delivery_orders'] }}</h4>
                        </div>
                        <div class="icon-circle" style="background: #e2e3e5; color: #6c757d;">
                            <i class="ti ti-truck fs-3"></i>
                        </div>
                    </div>
                    @if($stats['out_for_delivery_orders'] > 0)
                        <a href="{{ route('admin.orders.index', ['status' => 'out_for_delivery']) }}" class="btn btn-sm btn-secondary w-100 mt-3">
                            <i class="ti ti-eye"></i> عرض
                        </a>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Order Types & Products -->
    <div class="row g-3 mb-4">
        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">طلبات فورية</p>
                            <h4 class="mb-0 fw-bold text-success">{{ $stats['instant_orders'] }}</h4>
                        </div>
                        <div class="icon-circle soft-success">
                            <i class="ti ti-bolt fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">طلبات حجز</p>
                            <h4 class="mb-0 fw-bold" style="color: #17a2b8;">{{ $stats['reservation_orders'] }}</h4>
                        </div>
                        <div class="icon-circle" style="background: #d1ecf1; color: #17a2b8;">
                            <i class="ti ti-calendar-event fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">تم التوصيل</p>
                            <h4 class="mb-0 fw-bold text-success">{{ $stats['delivered_orders'] }}</h4>
                        </div>
                        <div class="icon-circle soft-success">
                            <i class="ti ti-circle-check fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <div class="col-md-3">
            <div class="card stat-card border-0 shadow-sm">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <p class="text-muted mb-1">المنتجات</p>
                            <h4 class="mb-0 fw-bold">{{ $stats['total_products'] }}</h4>
                            @if($stats['low_stock_products'] > 0)
                                <small class="text-danger">⚠️ {{ $stats['low_stock_products'] }} مخزون قليل</small>
                            @endif
                        </div>
                        <div class="icon-circle soft-primary">
                            <i class="ti ti-box fs-3"></i>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- Recent Orders -->
    <div class="row">
        <div class="col-12">
            <div class="card border-0 shadow-sm">
                <div class="card-header bg-white border-0 pb-0">
                    <div class="d-flex justify-content-between align-items-center">
                        <h5 class="mb-0 fw-bold">آخر الطلبات</h5>
                        <a href="{{ route('admin.orders.index') }}" class="btn btn-sm btn-outline-primary">
                            عرض الكل <i class="ti ti-arrow-left ms-1"></i>
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if($recentOrders->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover align-middle">
                                <thead class="table-light">
                                    <tr>
                                        <th>رقم الطلب</th>
                                        <th>العميل</th>
                                        <th>النوع</th>
                                        <th>الحالة</th>
                                        <th>المبلغ</th>
                                        <th>التاريخ</th>
                                        <th>الإجراءات</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($recentOrders as $order)
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
                                        <tr>
                                            <td><strong>#{{ $order->id }}</strong></td>
                                            <td>
                                                <div>{{ $order->customer_name }}</div>
                                                <small class="text-muted">{{ $order->customer_phone }}</small>
                                            </td>
                                            <td>
                                                @if($order->order_type == 'instant')
                                                    <span class="badge bg-success-subtle text-success">تسليم فوري</span>
                                                @else
                                                    <span class="badge bg-info-subtle text-info">حجز</span>
                                                @endif
                                            </td>
                                            <td>
                                                <span class="badge bg-{{ $statusColors[$order->status] }}">
                                                    {{ $statusLabels[$order->status] }}
                                                </span>
                                            </td>
                                            <td><strong>{{ number_format($order->total, 2) }} د.ل</strong></td>
                                            <td>
                                                <div>{{ $order->created_at->format('Y-m-d') }}</div>
                                                <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                            </td>
                                            <td>
                                                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn btn-sm btn-outline-primary">
                                                    <i class="ti ti-eye"></i>
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <div class="text-center py-5 text-muted">
                            <i class="ti ti-shopping-cart fs-1 mb-2 d-block opacity-50"></i>
                            <p>لا توجد طلبات بعد</p>
                        </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
</div>
@endsection

@push('styles')
<style>
.stat-card {
    transition: transform .15s ease, box-shadow .15s ease;
}
.stat-card:hover {
    transform: translateY(-3px);
    box-shadow: 0 8px 25px rgba(0,0,0,.12) !important;
}
.icon-circle {
    width: 56px;
    height: 56px;
    border-radius: 50%;
    display: flex;
    align-items: center;
    justify-content: center;
}
.soft-success { background: #e9f9ef; color: #28a745; }
.soft-danger { background: #fdeaea; color: #dc3545; }
.soft-primary { background: #e7f1ff; color: #0d6efd; }
.soft-info { background: #d1ecf1; color: #0dcaf0; }
.fs-2 { font-size: 1.8rem !important; }
.fs-3 { font-size: 1.5rem !important; }
.bg-success-subtle { background-color: #d1e7dd !important; }
.text-success { color: #198754 !important; }
.bg-info-subtle { background-color: #cff4fc !important; }
.text-info { color: #0dcaf0 !important; }
</style>
@endpush
