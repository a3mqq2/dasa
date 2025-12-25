<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Order;
use App\Models\Product;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        // Orders statistics
        $todayOrders = Order::whereDate('created_at', today())->count();
        $pendingOrders = Order::where('status', 'pending')->count();
        $confirmedOrders = Order::where('status', 'confirmed')->count();
        $preparingOrders = Order::where('status', 'preparing')->count();
        $outForDeliveryOrders = Order::where('status', 'out_for_delivery')->count();
        $deliveredOrders = Order::where('status', 'delivered')->count();
        $totalOrders = Order::count();

        // Revenue statistics
        $todayRevenue = Order::whereDate('created_at', today())
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');

        $monthRevenue = Order::whereYear('created_at', now()->year)
            ->whereMonth('created_at', now()->month)
            ->whereNotIn('status', ['cancelled'])
            ->sum('total');

        $totalRevenue = Order::whereNotIn('status', ['cancelled'])->sum('total');

        // Product statistics
        $totalProducts = Product::where('is_active', 1)->count();
        $lowStockProducts = Product::whereHas('stock', function($q) {
            $q->havingRaw('SUM(quantity) < 5');
        })->count();

        // Recent orders
        $recentOrders = Order::with('items')
            ->latest()
            ->take(5)
            ->get();

        // Order type breakdown
        $instantOrders = Order::where('order_type', 'instant')->count();
        $reservationOrders = Order::where('order_type', 'reservation')->count();

        $stats = [
            'users_active'   => User::where('is_active', 1)->count(),
            'users_inactive' => User::where('is_active', 0)->count(),
            'today_orders' => $todayOrders,
            'pending_orders' => $pendingOrders,
            'confirmed_orders' => $confirmedOrders,
            'preparing_orders' => $preparingOrders,
            'out_for_delivery_orders' => $outForDeliveryOrders,
            'delivered_orders' => $deliveredOrders,
            'total_orders' => $totalOrders,
            'today_revenue' => $todayRevenue,
            'month_revenue' => $monthRevenue,
            'total_revenue' => $totalRevenue,
            'total_products' => $totalProducts,
            'low_stock_products' => $lowStockProducts,
            'instant_orders' => $instantOrders,
            'reservation_orders' => $reservationOrders,
        ];

        return view('admin.dashboard', compact('stats', 'recentOrders'));
    }
}
