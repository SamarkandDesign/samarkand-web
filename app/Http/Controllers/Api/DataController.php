<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Repositories\Order\OrderRepository;

class DataController extends Controller
{
    protected $orders;

    public function __construct(OrderRepository $orders)
    {
        $this->orders = $orders;
    }

    public function sales()
    {
        $data = $this->orders->salesByMonth(); // key-value => date/amount

        return [
            'labels' => $data->pluck('date')->map(function ($date) {
                return $date->format('M Y');
            }),
            'datasets' => [
                [
                'data'  => $data->pluck('amount'),
                'label' => 'Total sales value by month',
                ],
            ],
        ];
    }
}
