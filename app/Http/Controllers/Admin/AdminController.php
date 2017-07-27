<?php

namespace App\Http\Controllers\Admin;

use App\Event;
use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;
use App\Repositories\Product\ProductRepository;
use Illuminate\Http\Request;

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
            'upcomingEvents'     => Event::upcoming()->count(),
        ]);
    }

    /**
     * Show the login page for the admin area.
     *
     * @param Request $request
     *
     * @return \Illuminate\Http\Response
     */
    public function login(Request $request)
    {
        if (!$request->session()->has('url.intended')) {
            $request->session()->flash('url.intended', 'admin');
        }

        return view('admin.login');
    }
}
