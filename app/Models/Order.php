<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Order extends Model
{
    use SoftDeletes;

    protected $table = 'orders';
    protected $with = ['orderItems'];

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'customer_id',
        'order_number',
        'status',
        'total_amount',
        'advance_paid',
        'delivery_date',
        'close_date',
        'notes',
    ];

    protected $casts = [
        'notes' => 'array',
        'delivery_date' => 'date',
        'close_date' => 'date',
        'total_amount' => 'decimal:2',
        'advance_paid' => 'decimal:2',

    ];


    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
    public function customer()
    {
        return $this->belongsTo(Customer::class, 'customer_id');
    }
    public function orderItems()
    {
        return $this->hasMany(OrderItem::class, 'order_id');
    }


    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn ($value) => ucwords($value),
            set: fn ($value) => strtolower($value),
        );
    }

}
