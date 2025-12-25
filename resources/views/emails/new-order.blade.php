<!DOCTYPE html>
<html dir="rtl" lang="ar">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <style>
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f4f4f4;
            margin: 0;
            padding: 20px;
            direction: rtl;
        }
        .container {
            max-width: 600px;
            margin: 0 auto;
            background: #fff;
            border-radius: 10px;
            overflow: hidden;
            box-shadow: 0 2px 10px rgba(0,0,0,0.1);
        }
        .header {
            background: linear-gradient(135deg, #f5476b 0%, #e03858 100%);
            color: #fff;
            padding: 30px 20px;
            text-align: center;
        }
        .header h1 {
            margin: 0;
            font-size: 24px;
        }
        .header p {
            margin: 10px 0 0;
            font-size: 16px;
            opacity: 0.9;
        }
        .content {
            padding: 30px 20px;
        }
        .order-info {
            background: #f8f9fa;
            border-radius: 8px;
            padding: 20px;
            margin-bottom: 20px;
        }
        .info-row {
            display: flex;
            justify-content: space-between;
            padding: 10px 0;
            border-bottom: 1px dashed #ddd;
        }
        .info-row:last-child {
            border-bottom: none;
        }
        .info-label {
            font-weight: 600;
            color: #5b6b79;
        }
        .info-value {
            color: #2c3e50;
        }
        .order-items {
            margin-top: 20px;
        }
        .order-items h3 {
            color: #2c3e50;
            margin-bottom: 15px;
        }
        .item {
            background: #fff;
            border: 1px solid #e0e0e0;
            border-radius: 8px;
            padding: 15px;
            margin-bottom: 10px;
        }
        .item-header {
            font-weight: 600;
            color: #2c3e50;
            margin-bottom: 5px;
        }
        .item-details {
            font-size: 14px;
            color: #6c757d;
        }
        .total-section {
            background: #151f42;
            color: #fff;
            padding: 20px;
            border-radius: 8px;
            margin-top: 20px;
        }
        .total-row {
            display: flex;
            justify-content: space-between;
            padding: 8px 0;
        }
        .total-label {
            font-weight: 600;
        }
        .grand-total {
            font-size: 20px;
            font-weight: 700;
            border-top: 2px solid rgba(255,255,255,0.3);
            padding-top: 15px;
            margin-top: 10px;
        }
        .footer {
            background: #f8f9fa;
            padding: 20px;
            text-align: center;
            color: #6c757d;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 12px 30px;
            background: #f5476b;
            color: #fff;
            text-decoration: none;
            border-radius: 6px;
            margin-top: 20px;
            font-weight: 600;
        }
        .status-badge {
            display: inline-block;
            padding: 5px 15px;
            border-radius: 20px;
            font-size: 14px;
            font-weight: 600;
        }
        .badge-warning {
            background: #ffc107;
            color: #000;
        }
        .badge-success {
            background: #28a745;
            color: #fff;
        }
        .badge-info {
            background: #17a2b8;
            color: #fff;
        }
    </style>
</head>
<body>
    <div class="container">
        <!-- Header -->
        <div class="header">
            <h1>🎂 طلب جديد - Dasa's Cake</h1>
            <p>تم استلام طلب جديد من العميل</p>
        </div>

        <!-- Content -->
        <div class="content">
            <!-- Order Information -->
            <div class="order-info">
                <div class="info-row">
                    <span class="info-label">رقم الطلب</span>
                    <span class="info-value" style="font-size: 18px; font-weight: 700; color: #f5476b;">#{{ $order->id }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">الحالة</span>
                    <span class="info-value">
                        <span class="status-badge badge-warning">قيد الانتظار</span>
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">نوع الطلب</span>
                    <span class="info-value">
                        @if($order->order_type == 'instant')
                            <span class="status-badge badge-success">تسليم فوري</span>
                        @else
                            <span class="status-badge badge-info">حجز</span>
                        @endif
                    </span>
                </div>
                <div class="info-row">
                    <span class="info-label">التاريخ</span>
                    <span class="info-value">{{ $order->created_at->format('Y-m-d H:i') }}</span>
                </div>
                @if($order->order_type == 'reservation' && $order->delivery_date)
                <div class="info-row">
                    <span class="info-label">تاريخ التسليم المطلوب</span>
                    <span class="info-value">{{ $order->delivery_date }}</span>
                </div>
                @endif
            </div>

            <!-- Customer Information -->
            <div class="order-info">
                <h3 style="margin-top: 0; color: #2c3e50;">معلومات العميل</h3>
                <div class="info-row">
                    <span class="info-label">الاسم</span>
                    <span class="info-value">{{ $order->customer_name }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">رقم الهاتف</span>
                    <span class="info-value">{{ $order->customer_phone }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">العنوان</span>
                    <span class="info-value">{{ $order->delivery_address }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">نوع التوصيل</span>
                    <span class="info-value">{{ $order->delivery_type == 'male' ? 'رجالي' : 'نسائي' }}</span>
                </div>
                <div class="info-row">
                    <span class="info-label">طريقة الدفع</span>
                    <span class="info-value">{{ $order->payment_method == 'cash' ? 'كاش' : 'حوالة بنكية' }}</span>
                </div>
            </div>

            <!-- Order Items -->
            <div class="order-items">
                <h3>المنتجات المطلوبة</h3>
                @foreach($order->items as $item)
                <div class="item">
                    <div class="item-header">{{ $item->product_name }}</div>
                    <div class="item-details">
                        @if($item->variant_combination)
                            <div style="margin-top: 5px;">
                                @php
                                    $variants = json_decode($item->variant_combination, true);
                                @endphp
                                @if($variants)
                                    @foreach($variants as $key => $value)
                                        <span style="background: #e9ecef; padding: 3px 10px; border-radius: 4px; margin-left: 5px; display: inline-block; margin-top: 5px;">
                                            {{ $key }}: {{ $value }}
                                        </span>
                                    @endforeach
                                @endif
                            </div>
                        @endif
                        <div style="margin-top: 8px; display: flex; justify-content: space-between;">
                            <span>السعر: {{ number_format($item->product_price, 2) }} د.ل</span>
                            <span>الكمية: {{ $item->quantity }}</span>
                            <span style="font-weight: 700;">الإجمالي: {{ number_format($item->subtotal, 2) }} د.ل</span>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            <!-- Total Section -->
            <div class="total-section">
                <div class="total-row">
                    <span class="total-label">المجموع الفرعي</span>
                    <span>{{ number_format($order->subtotal, 2) }} د.ل</span>
                </div>
                <div class="total-row">
                    <span class="total-label">رسوم التوصيل</span>
                    <span>{{ number_format($order->delivery_fee, 2) }} د.ل</span>
                </div>
                @if($order->deposit_amount)
                <div class="total-row" style="color: #ffc107;">
                    <span class="total-label">العربون المطلوب (50%)</span>
                    <span style="font-weight: 700;">{{ number_format($order->deposit_amount, 2) }} د.ل</span>
                </div>
                @endif
                <div class="total-row grand-total">
                    <span class="total-label">الإجمالي الكلي</span>
                    <span>{{ number_format($order->total, 2) }} د.ل</span>
                </div>
            </div>

            <!-- Action Button -->
            <div style="text-align: center;">
                <a href="{{ route('admin.orders.show', $order->id) }}" class="btn">
                    عرض تفاصيل الطلب
                </a>
            </div>
        </div>

        <!-- Footer -->
        <div class="footer">
            <p>هذا البريد الإلكتروني تم إرساله تلقائياً من نظام Dasa's Cake</p>
            <p style="margin-top: 5px; font-size: 12px;">يرجى عدم الرد على هذا البريد</p>
        </div>
    </div>
</body>
</html>
