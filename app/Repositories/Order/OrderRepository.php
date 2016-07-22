<?php

namespace App\Repositories\Order;

interface OrderRepository
{
    /**
     * @param int   $id
     * @param array $with
     *
     * @return mixed
     */
    public function fetch($id, $with = []);

    /**
     * Get a count of all orders in the database.
     *
     * @return int
     */
    public function count($status = null);

    /**
     * Get a count of orders by their status.
     *
     * @return int
     */
    public function countByStatus();

    /**
     * Get the total sales value by month
     * @return Collection
     */
    public function salesByMonth();
}
