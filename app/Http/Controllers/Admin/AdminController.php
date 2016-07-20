<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;

class AdminController extends Controller
{
    protected $orders;
    protected $products;

    public function __construct(OrderRepository $orders, ProductRepository $products)
    {
        $this->orders = $orders;
        $this->products = $products;
    }

    public function dashboard()
    {
        return view('admin.dashboard', [
            'orderCountByStatus' => $this->orders->countByStatus(),
            'lowStockedProducts' => $this->products->countLowStock(),
            'outOfStockProducts' => $this->products->countOutOfStock(),
        ]);
    }
}
