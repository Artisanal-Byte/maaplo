<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Database\Eloquent\Casts\Attribute;

class Template extends Model
{
    use SoftDeletes;

    protected $table = 'templates';

    protected $fillable = [
        'name',
        'gender',
        'body_part',
        'svg_logo',
        'design_details',
        'user_id',
    ];

    protected $casts = [
        'custom_template' => 'boolean',
        'design_details' => 'array',
    ];

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return match ($value) {
                    'm' => 'Male',
                    'f' => 'Female',
                    'o' => 'Other',
                };
            },
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucfirst(strtolower($value)),
            set: fn($value) => ucfirst(strtolower($value))
        );
    }

    protected function bodyPart(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                return ucfirst(strtolower($value));
            },
        );
    }

    // Relationships
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id');
    }

    protected static function booted()
    {
        // When an ItemTemplate is soft-deleted
        static::deleting(function ($itemTemplate): void {
            if (!$itemTemplate->isForceDeleting()) {
            }
        });
    }

    public function measurements()
    {
        return $this->belongsToMany(
            Measurement::class,
            'templates_measurements',
            'template_id',
            'measurements_id'
        );
    }

    protected function designDetailsList(): Attribute
    {
        return Attribute::get(
            fn() => DesignDetail::whereIn('id', $this->design_details ?? [])->get()
        );
    }
}
