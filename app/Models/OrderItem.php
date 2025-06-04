<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class OrderItem extends Model
{
    use SoftDeletes;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'item_template_id',
        'name',
        'measurements',
        'design_detail',
        'colors',
        'material',
        'trial_dates',
        'price',
        'status',
        'is_urgent',         // ✅ Add this
        'item_cost',
        'material_code',
        'material_cost',
        'material_type',
        'notes',
        'refrence_dress',
        'stiching_cost',
        'altering_cost',
        'delivery_date',
        'work_type',
    ];

    protected $casts = [
        'measurements' => 'array',
        'design_detail' => 'array',
        'trial_dates' => 'date',
        'price' => 'decimal:2',
        // 'is_urgent' => 'boolean',
    ];

    // Relationships

    /**
     * Get the order that owns the order item.
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Get the item template associated with the order item.
     */
    public function Template()
    {
        return $this->belongsTo(Template::class);
    }

    // Accessors & Mutators

    /**
     * Get the formatted status.
     */
    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst(str_replace('_', ' ', $value)),
            set: fn(string $value) => strtolower(str_replace(' ', '_', $value))
        );
    }
}
