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
        'body_part_id',
        'value',
        'image',
        'gender',
    ];

    protected $casts = [
        'design_details' => 'array',
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

    private function convertForDisplay(?string $value): string
    {
        if (is_null($value)) {
            return '';
        }

        return ucwords(strtolower(str_replace('_', ' ', $value)));
    }

    public function bodyPartValue()
    {
        return $this->belongsTo(BodyPartValue::class, 'body_part_id');
    }
}
