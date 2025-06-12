<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class SubscriptionPlan extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'plan_title',
        'plan_description',
        'plan_price',
        'plan_currency',
        'features',
        'visibility',
        'user_limit',
    ];

    protected $casts = [
        'features' => 'array',
        'visibility' => 'boolean',
    ];
}
