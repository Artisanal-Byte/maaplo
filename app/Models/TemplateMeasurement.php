<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TemplateMeasurement extends Model
{
    use HasFactory;

    protected $table = 'templates_measurements';

    protected $primaryKey = 'id';

    protected $fillable = [
        'measurements_id',
        'template_id',
    ];

    protected $casts = [
        'measurements_id' => 'integer',
        'template_id' => 'integer',
    ];

    public function measurement()
    {
        return $this->belongsTo(Measurement::class, 'measurements_id');
    }

    public function template()
    {
        return $this->belongsTo(Template::class, 'template_id');
    }
}
