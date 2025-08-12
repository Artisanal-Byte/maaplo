<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class OrderItem extends Model
{
    use HasFactory, SoftDeletes;

    protected $table = 'order_items';

    protected $fillable = [
        'order_id',
        'template_id',
        'item_template_id',
        'name',
        'measurements',
        'design_detail',
        'colors',
        'material',
        'trial_dates',
        'price',
        'status',
        'is_urgent',
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
        'cloth_img1',
        'cloth_img2',
        'Pattern_img1',
        'Pattern_img2'
    ];

    protected $casts = [
        'measurements' => 'array',
        'design_detail' => 'array',
        'trial_dates' => 'date',
        'price' => 'decimal:2',
        'is_urgent' => 'boolean',
    ];

    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function Template()
    {
        return $this->belongsTo(Template::class);
    }

    public function getDeliveryDateAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }
    public function getTrialDatesAttribute($value)
    {
        return Carbon::parse($value)->format('d-m-Y');
    }


    protected function status(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst(str_replace('_', ' ', $value)),
            set: fn(string $value) => strtolower(str_replace(' ', '_', $value))
        );
    }
}
