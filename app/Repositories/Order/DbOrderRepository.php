<?php

namespace App\Repositories\Order;

use App\Order;
use App\Repositories\DbRepository;

class DbOrderRepository extends DbRepository implements OrderRepository
{
    public function __construct(Order $order)
    {
        $this->model = $order;
    }

    public function count($status = null)
    {
        if (!$status) {
            return $this->model->count();
        }

        return $this->model->where('status', $status)->count();
    }

    /**
     * Get a count of orders by their status.
     *
     * @return int
     */
    public function countByStatus()
    {
        return $this->model
        ->select('status', \DB::raw('COUNT(*) as count'))
        ->groupBy('status')
        ->lists('count', 'status');
    }

    public function salesByMonth()
    {
        $results = $this->model
        ->select('created_at', 'amount')
        ->orderBy('created_at')
        ->get()
        ->groupBy(function ($order) {
            return $order->created_at->endOfMonth()->toDateTimeString();
        })->map(function ($period) {
            return $period->sum(function ($order) {
                return  $order->amount->value() / 100;
            });
        });
        // return $results;
        return $results->map(function ($amount, $period) {
            return [
                'amount' => $amount,
                'date'   => \Carbon\Carbon::parse($period),
            ];
        });
    }
}
