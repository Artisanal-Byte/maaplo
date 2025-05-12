<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMeasurement extends Model
{
    use HasFactory;

    // The table associated with the model
    protected $table = 'templates_measurements';

    // The primary key associated with the table
    protected $primaryKey = 'id';

    // The attributes that are mass assignable
    protected $fillable = [
        'measurements_id',
        'template_id',
    ];

    // The attributes that should be cast
    protected $casts = [
        'measurements_id' => 'integer',
        'template_id' => 'integer',
    ];

    // Define the relationship with the Measurement model
    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurements_id');
    }

    // Define the relationship with the Template model
    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}
