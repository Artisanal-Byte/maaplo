<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BodyPartValue extends Model
{
    protected $table = 'body_part_value';
    protected $fillable = ['body_part'];
}
