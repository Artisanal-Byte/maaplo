<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Casts\Attribute;
class BodyPartValue extends Model
{
    protected $table = 'body_part_value';
    protected $fillable = ['body_part'];

    protected function bodyPart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => ucwords(str_replace('_', ' ', $value)),
            set: fn($value) => strtolower(str_replace(' ', '_', trim($value)))
        );
    }
}
