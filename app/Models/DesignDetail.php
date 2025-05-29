<?php

namespace App\Models;

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
}
