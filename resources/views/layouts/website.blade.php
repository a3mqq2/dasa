<!DOCTYPE html>
<html lang="ar" dir="rtl">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Dasa\'s Cake - حلويات داسا')</title>

    <link rel="icon" href="{{ asset('assets/images/favicon.svg') }}" type="image/x-icon">
    <link href="https://fonts.googleapis.com/css2?family=Changa:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/fonts/phosphor/duotone/style.css') }}">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.min.css">

    <style>
        :root {
            --primary-color: #f5476b;
            --secondary-color: #151f42;
            --light-bg: #fef5f7;
            --border-color: #fad4dc;
        }

        body {
            font-family: 'Changa', sans-serif;
            background: #fff;
            color: #333;
            min-height: 100vh;
        }

        /* Logo Section */
        .logo-section {
            text-align: center;
            padding: 2rem 0 1rem;
        }

        .logo-section img {
            max-width: 200px;
            height: auto;
        }

        /* Cards */
        .product-card {
            border: 1px solid var(--border-color);
            border-radius: 12px;
            overflow: hidden;
            transition: transform 0.2s, box-shadow 0.2s;
            height: 100%;
        }

        .product-card:hover {
            transform: translateY(-4px);
            box-shadow: 0 8px 20px rgba(0,0,0,0.08);
        }

        .product-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .product-placeholder {
            width: 100%;
            height: 250px;
            background: var(--light-bg);
            display: flex;
            align-items: center;
            justify-content: center;
        }

        /* Buttons */
        .btn-primary {
            background: var(--primary-color);
            border-color: var(--primary-color);
        }

        .btn-primary:hover {
            background: #e03858;
            border-color: #e03858;
        }

        .btn-secondary {
            background: var(--secondary-color);
            border-color: var(--secondary-color);
        }

        .btn-secondary:hover {
            background: #0f1830;
            border-color: #0f1830;
        }

        /* App-like Layout */
        body {
            padding-bottom: 80px;
        }

        /* Fixed Bottom Navigation */
        .bottom-nav {
            position: fixed;
            bottom: 0;
            left: 0;
            right: 0;
            background: #fff;
            border-top: 2px solid var(--border-color);
            box-shadow: 0 -5px 20px rgba(0, 0, 0, 0.1);
            z-index: 1000;
            padding: 0.75rem 0;
        }

        .nav-container {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            max-width: 600px;
            margin: 0 auto;
        }

        .nav-item {
            text-decoration: none;
            color: #6c757d;
            transition: all 0.3s;
            position: relative;
            display: inline-flex;
            flex-direction: row;
            align-items: center;
            justify-content: center;
            gap: 0.5rem;
            padding: 0.5rem 1rem;
            white-space: nowrap;
        }

        .nav-item i {
            font-size: 1.5rem;
            transition: all 0.3s;
        }

        .nav-item span {
            font-size: 0.9rem;
            font-weight: 600;
        }

        .nav-item:hover,
        .nav-item.active {
            color: var(--primary-color);
            transform: translateY(-2px);
        }

        .nav-item .badge {
            position: absolute;
            top: 5px;
            left: 5px;
            background: var(--primary-color);
            color: #fff;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 0.65rem;
            font-weight: 700;
            border: 2px solid #fff;
        }

        /* SweetAlert2 Customization */
        .swal2-popup {
            font-family: 'Changa', sans-serif !important;
            direction: rtl;
        }

        .swal2-confirm {
            background: var(--primary-color) !important;
            border-color: var(--primary-color) !important;
        }

        .swal2-confirm:hover {
            background: #e03858 !important;
            border-color: #e03858 !important;
        }

        .swal2-cancel {
            background: #6c757d !important;
        }
    </style>

    @stack('styles')
</head>
<body>
    <!-- Logo -->
    <div class="logo-section">
        <a href="{{ route('home') }}">
            <img src="{{ asset('logo-primary.png') }}" alt="Dasa's Cake">
        </a>
    </div>

    <!-- Messages -->
    <div class="container">
        @include('layouts.messages')
    </div>

    <!-- Main Content -->
    <main class="container my-4">
        @yield('content')
    </main>

    <!-- Bottom Navigation -->
    @php
        $cart = session('cart', ['items' => []]);
        $cartCount = count($cart['items']);
        $currentRoute = Route::currentRouteName();
    @endphp

    <nav class="bottom-nav">
        <div class="nav-container">
            <a href="{{ route('home') }}" class="nav-item {{ $currentRoute == 'home' ? 'active' : '' }}">
                <i class="ph-duotone ph-house"></i>
                <span>الرئيسية</span>
            </a>

            <a href="{{ route('cart.index') }}" class="nav-item {{ in_array($currentRoute, ['cart.index', 'checkout', 'instant', 'reservation']) ? 'active' : '' }}">
                <i class="ph-duotone ph-shopping-cart"></i>
                @if($cartCount > 0)
                    <span class="badge">{{ $cartCount }}</span>
                @endif
                <span>السلة</span>
            </a>

            <a href="{{ route('about') }}" class="nav-item {{ $currentRoute == 'about' ? 'active' : '' }}">
                <i class="ph-duotone ph-info"></i>
                <span>معلومات</span>
            </a>
        </div>
    </nav>

    <script src="{{ asset('assets/js/plugins/popper.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/js/plugins/bootstrap.min.js') }}"></script>
    <script src="{{ asset('assets/js/config.js') }}"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <script>
        // SweetAlert for Laravel Messages
        @if(session('cart_added'))
            Swal.fire({
                icon: 'success',
                title: 'تمت الإضافة!',
                text: '{{ session('cart_added') }}',
                showCancelButton: true,
                confirmButtonText: '<i class="ph-duotone ph-shopping-cart me-2"></i> عرض السلة',
                cancelButtonText: '<i class="ph-duotone ph-arrow-right me-2"></i> متابعة التسوق',
                reverseButtons: true,
            }).then((result) => {
                if (result.isConfirmed) {
                    window.location.href = '{{ route('cart.index') }}';
                }
            });
        @endif

        @if(session('success'))
            Swal.fire({
                icon: 'success',
                title: 'نجح!',
                text: '{{ session('success') }}',
                confirmButtonText: 'حسناً',
                timer: 3000,
                timerProgressBar: true,
            });
        @endif

        @if(session('error'))
            Swal.fire({
                icon: 'error',
                title: 'خطأ!',
                text: '{{ session('error') }}',
                confirmButtonText: 'حسناً',
            });
        @endif

        @if(session('warning'))
            Swal.fire({
                icon: 'warning',
                title: 'تنبيه!',
                text: '{{ session('warning') }}',
                confirmButtonText: 'حسناً',
            });
        @endif

        @if(session('info'))
            Swal.fire({
                icon: 'info',
                title: 'معلومة',
                text: '{{ session('info') }}',
                confirmButtonText: 'حسناً',
            });
        @endif

        @if($errors->any())
            Swal.fire({
                icon: 'error',
                title: 'خطأ في البيانات!',
                html: '<ul style="text-align: right; list-style: none; padding: 0;">@foreach($errors->all() as $error)<li>{{ $error }}</li>@endforeach</ul>',
                confirmButtonText: 'حسناً',
            });
        @endif
    </script>

    @stack('scripts')
</body>
</html>
