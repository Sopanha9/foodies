<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MenuItem;
use App\Models\Order;
use Illuminate\Support\Facades\Schema;

class DashboardController extends Controller
{
    public function index()
    {
        if (! Schema::hasTable('orders') || ! Schema::hasTable('menu_items')) {
            return view('pages.admin.dashboard', [
                'totalOrders' => 0,
                'pendingOrders' => 0,
                'menuItems' => 0,
            ]);
        }

        return view('pages.admin.dashboard', [
            'totalOrders' => Order::query()->count(),
            'pendingOrders' => Order::query()->where('order_status', 'pending')->count(),
            'menuItems' => MenuItem::query()->count(),
        ]);
    }
}
