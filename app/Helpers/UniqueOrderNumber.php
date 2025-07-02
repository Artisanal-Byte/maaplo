<?php

namespace App\Helpers;

use App\Models\Order;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

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

        $userId = Auth::id();
        $maxOrderNumber = Order::withTrashed()
            ->where('user_id', $userId)
            ->max(DB::raw('CAST(order_number AS INTEGER)'));
        $nextOrderNumber = $maxOrderNumber ? $maxOrderNumber + 1 : 1;

        $uniqueOrderNumber = str_pad($nextOrderNumber, 6, '0', STR_PAD_LEFT);

        if ($this->isUnique($uniqueOrderNumber, $userId) === false) {
            throw new \Exception('Generated order number is not unique.');
        }

        return $uniqueOrderNumber;
    }

    public function isUnique($orderNumber, int $userId): bool
    {
        return !Order::where('user_id', $userId)
            ->where('order_number', $orderNumber)
            ->exists();
    }
}
