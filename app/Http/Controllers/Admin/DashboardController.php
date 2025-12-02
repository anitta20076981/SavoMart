<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepositoryInterface as OrderRepository;


class DashboardController extends Controller
{
    public function home( OrderRepository $orderRepo)
    {

        $data['toplistingProducts'] = $orderRepo->topSellingProducts();

        $data['recentOrders'] = $orderRepo->recentOrders();

        return view('admin.dashboard.home',compact('data') );
    }
}