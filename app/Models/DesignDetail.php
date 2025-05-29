<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class DesignDetail extends Model
{
    use SoftDeletes;
    protected $table = 'design_details';
    protected $fillable = [
        'body_section',
        'body_part',
        'value',
        'image',
        'gender',
    ];

    protected function bodyPart(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->convertForDisplay($value),
            set: fn($value) => $this->convertForStorage($value),
        );
    }

    protected function value(): Attribute
    {
        return Attribute::make(
            get: fn($value) => $this->convertForDisplay($value),
            set: fn($value) => $this->convertForStorage($value),
        );
    }

    private function convertForStorage(string $value): string
    {
        return strtolower(str_replace(' ', '_', trim($value)));
    }

    private function convertForDisplay(string $value): string
    {
        return ucwords(strtolower(str_replace('_', ' ', $value)));
    }
}
