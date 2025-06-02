<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Measurement extends Model
{
  use HasFactory;

  protected $table = 'measurements';
  protected $primaryKey = 'id';

  // protected function name(): Attribute
  // {
  //   return Attribute::make(
  //     get: fn() => Str::ucfirst(Str::replace("_", " ", $this->slug))
  //   );
  // }
  // protected $appends = ["name"];
  protected $fillable = [
    'slug',
    'logo',
  ];

  public function templates()
  {
    return $this->belongsToMany(Template::class, 'templates_measurements', 'measurement_id', 'template_id');
  }
}
