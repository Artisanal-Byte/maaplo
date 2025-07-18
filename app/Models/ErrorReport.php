<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Str;

class ErrorReport extends Model
{
    use HasFactory, SoftDeletes;
    protected $fillable = [
        'description',
        'screenshot_path',
        'url',
        'error_type',
        'other_error_type',
    ];

    public function setErrorTypeAttribute($value)
    {
        // Optional: if you want to map only some strings, use a map like in the controller.
        // Otherwise, just convert spaces/slashes to underscore & lowercase:
        $snake = Str::snake(str_replace('/', '_', $value));
        $this->attributes['error_type'] = $snake;
    }
}
