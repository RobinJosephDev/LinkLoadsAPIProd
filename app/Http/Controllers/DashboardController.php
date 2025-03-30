<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Order;
use App\Models\Shipment;
use App\Models\Customer;

class DashboardController extends Controller
{
    public function getDashboardData()
    {
        $totalOrders = Order::count();
        $totalShipments = Shipment::count();
        $totalRevenue = Order::sum('final_price');
        $activeCustomers = Customer::count();

        $orderTrends = Order::selectRaw('DATE(created_at) as date, COUNT(*) as orders')
            ->groupBy('date')
            ->orderBy('date', 'ASC')
            ->get();

        return response()->json([
            'totalOrders' => $totalOrders,
            'totalShipments' => $totalShipments,
            'totalRevenue' => $totalRevenue,
            'activeCustomers' => $activeCustomers,
            'orderTrends' => $orderTrends,
        ]);
    }
}
