<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Models\OrderItem;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index()
    {
        $today = Carbon::today();

        $ordersToday = Order::whereDate('order_time', $today)
            ->where('status', 'completed')
            ->get();

        $totalOrdersToday = $ordersToday->count();
        $incomeToday = $ordersToday->sum('total_price');

        $itemsToday = OrderItem::with(['product', 'order'])
            ->whereHas('order', function ($query) use ($today) {
                $query->whereDate('order_time', $today)
                    ->where('status', 'completed');
            })
            ->get();

        $profitToday = $itemsToday->sum(function ($item) {
            return ($item->selling_price - $item->hpp) * $item->quantity;
        });

        $bestSellingMenus = OrderItem::with('product')
            ->whereHas('order', function ($query) {
                $query->where('status', 'completed');
            })
            ->selectRaw('product_id, SUM(quantity) as total_sold')
            ->groupBy('product_id')
            ->orderByDesc('total_sold')
            ->limit(5)
            ->get();

        $chartLabels = $bestSellingMenus->map(function ($item) {
            return $item->product->name ?? '-';
        });

        $chartData = $bestSellingMenus->map(function ($item) {
            return $item->total_sold;
        });

        $recentOrders = Order::latest()
            ->limit(5)
            ->get();

        return view('dashboard.index', compact(
            'totalOrdersToday',
            'incomeToday',
            'profitToday',
            'bestSellingMenus',
            'recentOrders',
            'chartLabels',
            'chartData'
        ));
    }
}