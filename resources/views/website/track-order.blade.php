@extends('layouts.website')

@section('title', 'متابعة الطلب - Dasa\'s Cake')

@push('styles')
<style>
    .track-container {
        max-width: 800px;
        margin: 0 auto;
        padding: 2rem 0;
    }

    .search-card {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .search-card h3 {
        color: var(--primary-color);
        font-weight: 700;
        margin-bottom: 1.5rem;
        text-align: center;
    }

    .search-form {
        display: flex;
        gap: 1rem;
    }

    .search-input {
        flex: 1;
        padding: 1rem;
        border: 2px solid var(--border-color);
        border-radius: 10px;
        font-size: 1.1rem;
        text-align: center;
    }

    .search-input:focus {
        border-color: var(--primary-color);
        outline: none;
    }

    .search-btn {
        padding: 1rem 2rem;
        background: var(--primary-color);
        color: #fff;
        border: none;
        border-radius: 10px;
        font-weight: 600;
        cursor: pointer;
        transition: all 0.3s;
    }

    .search-btn:hover {
        background: #e03858;
    }

    .order-info-card {
        background: #fff;
        border: 2px solid var(--border-color);
        border-radius: 15px;
        padding: 2rem;
        margin-bottom: 2rem;
    }

    .order-header {
        display: flex;
        justify-content: space-between;
        align-items: center;
        padding-bottom: 1rem;
        border-bottom: 2px dashed var(--border-color);
        margin-bottom: 1.5rem;
    }

    .order-number {
        font-size: 1.5rem;
        font-weight: 700;
        color: var(--primary-color);
    }

    .order-status-badge {
        padding: 0.5rem 1.5rem;
        border-radius: 20px;
        font-weight: 600;
        font-size: 1rem;
    }

    .info-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(200px, 1fr));
        gap: 1.5rem;
        margin-bottom: 2rem;
    }

    .info-item {
        text-align: center;
    }

    .info-label {
        color: #6c757d;
        font-size: 0.9rem;
        margin-bottom: 0.5rem;
    }

    .info-value {
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 1.1rem;
    }

    /* Progress Steps */
    .progress-container {
        background: var(--light-bg);
        border-radius: 15px;
        padding: 2rem;
    }

    .progress-title {
        text-align: center;
        color: var(--secondary-color);
        font-weight: 700;
        font-size: 1.3rem;
        margin-bottom: 2rem;
    }

    .progress-steps {
        position: relative;
        padding: 0 1rem;
    }

    .progress-line {
        position: absolute;
        right: 2.5rem;
        top: 0;
        bottom: 0;
        width: 3px;
        background: var(--border-color);
    }

    .progress-line-fill {
        position: absolute;
        right: 0;
        top: 0;
        width: 100%;
        background: var(--primary-color);
        transition: height 0.5s ease;
    }

    .progress-step {
        position: relative;
        padding: 1.5rem 1.5rem 1.5rem 4rem;
        margin-bottom: 1rem;
    }

    .step-icon {
        position: absolute;
        right: 0;
        top: 1.5rem;
        width: 50px;
        height: 50px;
        border-radius: 50%;
        background: #fff;
        border: 3px solid var(--border-color);
        display: flex;
        align-items: center;
        justify-content: center;
        font-size: 1.5rem;
        transition: all 0.3s;
        z-index: 2;
    }

    .progress-step.completed .step-icon {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
    }

    .progress-step.active .step-icon {
        background: var(--primary-color);
        border-color: var(--primary-color);
        color: #fff;
        animation: pulse 1.5s infinite;
    }

    @keyframes pulse {
        0%, 100% {
            transform: scale(1);
            box-shadow: 0 0 0 0 rgba(245, 71, 107, 0.7);
        }
        50% {
            transform: scale(1.05);
            box-shadow: 0 0 0 10px rgba(245, 71, 107, 0);
        }
    }

    .step-content {
        background: #fff;
        padding: 1rem;
        border-radius: 10px;
        border: 1px solid var(--border-color);
    }

    .step-title {
        font-weight: 700;
        color: var(--secondary-color);
        margin-bottom: 0.5rem;
    }

    .progress-step.completed .step-title {
        color: var(--primary-color);
    }

    .progress-step.active .step-title {
        color: var(--primary-color);
    }

    .step-description {
        color: #6c757d;
        font-size: 0.9rem;
    }

    .step-time {
        color: #999;
        font-size: 0.85rem;
        margin-top: 0.5rem;
    }
</style>
@endpush

@section('content')
<div class="track-container">
    <!-- Search Form -->
    <div class="search-card">
        <h3><i class="ph-duotone ph-magnifying-glass me-2"></i>تتبع طلبك</h3>
        <form action="{{ route('order.track') }}" method="GET" class="search-form">
            <input type="number"
                   name="order_id"
                   class="search-input"
                   placeholder="أدخل رقم الطلب"
                   value="{{ request('order_id') }}"
                   required>
            <button type="submit" class="search-btn">
                <i class="ph-duotone ph-magnifying-glass me-2"></i>
                بحث
            </button>
        </form>
    </div>

    @if(isset($order))
        <!-- Order Information -->
        <div class="order-info-card">
            <div class="order-header">
                <span class="order-number">#{{ $order->id }}</span>
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
                        'out_for_delivery' => 'تم التسليم للمندوب',
                        'delivered' => 'تم التوصيل',
                        'cancelled' => 'ملغي'
                    ];
                @endphp
                <span class="order-status-badge bg-{{ $statusColors[$order->status] }} text-white">
                    {{ $statusLabels[$order->status] }}
                </span>
            </div>

            <div class="info-grid">
                <div class="info-item">
                    <div class="info-label">نوع الطلب</div>
                    <div class="info-value">
                        @if($order->order_type == 'instant')
                            تسليم فوري
                        @else
                            حجز
                        @endif
                    </div>
                </div>
                <div class="info-item">
                    <div class="info-label">الإجمالي</div>
                    <div class="info-value">{{ number_format($order->total, 2) }} د.ل</div>
                </div>
                <div class="info-item">
                    <div class="info-label">تاريخ الطلب</div>
                    <div class="info-value">{{ $order->created_at->format('Y-m-d') }}</div>
                </div>
                @if($order->order_type == 'reservation' && $order->delivery_date)
                    <div class="info-item">
                        <div class="info-label">تاريخ التسليم</div>
                        <div class="info-value">{{ $order->delivery_date }}</div>
                    </div>
                @endif
            </div>
        </div>

        <!-- Progress Steps -->
        @if($order->status != 'cancelled')
            <div class="progress-container">
                <h4 class="progress-title">مراحل الطلب</h4>

                @php
                    $steps = [
                        'pending' => ['icon' => 'ph-clock', 'title' => 'تم استلام الطلب', 'desc' => 'طلبك قيد المراجعة وسيتم التواصل معك قريباً'],
                        'confirmed' => ['icon' => 'ph-check-circle', 'title' => 'تمت الموافقة', 'desc' => 'تم تأكيد طلبك وجاري التحضير'],
                        'preparing' => ['icon' => 'ph-cooking-pot', 'title' => 'قيد التحضير', 'desc' => 'طلبك قيد التحضير الآن'],
                        'out_for_delivery' => ['icon' => 'ph-truck', 'title' => 'في الطريق', 'desc' => 'طلبك مع المندوب وفي طريقه إليك'],
                        'delivered' => ['icon' => 'ph-package', 'title' => 'تم التوصيل', 'desc' => 'تم توصيل طلبك بنجاح!']
                    ];

                    $statusOrder = ['pending', 'confirmed', 'preparing', 'out_for_delivery', 'delivered'];
                    $currentIndex = array_search($order->status, $statusOrder);
                    $progressPercentage = (($currentIndex + 1) / count($statusOrder)) * 100;
                @endphp

                <div class="progress-steps">
                    <div class="progress-line">
                        <div class="progress-line-fill" style="height: {{ $progressPercentage }}%"></div>
                    </div>

                    @foreach($steps as $key => $step)
                        @php
                            $stepIndex = array_search($key, $statusOrder);
                            $isCompleted = $stepIndex < $currentIndex;
                            $isActive = $stepIndex == $currentIndex;
                        @endphp
                        <div class="progress-step {{ $isCompleted ? 'completed' : '' }} {{ $isActive ? 'active' : '' }}">
                            <div class="step-icon">
                                <i class="ph-duotone {{ $step['icon'] }}"></i>
                            </div>
                            <div class="step-content">
                                <div class="step-title">{{ $step['title'] }}</div>
                                <div class="step-description">{{ $step['desc'] }}</div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        @else
            <div class="alert alert-danger text-center">
                <i class="ph-duotone ph-x-circle me-2"></i>
                <strong>تم إلغاء الطلب</strong>
            </div>
        @endif

        <!-- Contact Button -->
        <div class="text-center mt-4">
            <a href="https://wa.me/218910739550?text=مرحبا، استفسار عن طلب رقم {{ $order->id }}"
               target="_blank"
               class="btn btn-success btn-lg">
                <i class="ph-duotone ph-whatsapp-logo me-2"></i>
                تواصل معنا
            </a>
        </div>
    @elseif(request('order_id'))
        <div class="alert alert-warning text-center">
            <i class="ph-duotone ph-warning me-2"></i>
            لم يتم العثور على طلب برقم <strong>#{{ request('order_id') }}</strong>
        </div>
    @endif
</div>
@endsection
