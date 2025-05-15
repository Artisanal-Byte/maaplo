<?php

namespace App\Helpers;

use App\Models\Order;

class UniqueOrderNumber
{
    /**
     * Create a new class instance.
     */
    protected $lastOrderNumber;

    public function __construct()
    {
        $this->lastOrderNumber = Order::latest()->value('order_number');
    }

    public function make(): string
    {

        $nextOrderNumber = $this->lastOrderNumber + 1;

        $uniqueOrderNumber = str_pad($nextOrderNumber, 6, '0', STR_PAD_LEFT);

        if (!$this->isUnique($uniqueOrderNumber)) {
            throw new \Exception('The Order Number Getting Same');
        }

        return $uniqueOrderNumber;
    }


    public function isUnique($orderNumber): bool
    {
        return !Order::where('order_number', $orderNumber)->exists();
    }
}
