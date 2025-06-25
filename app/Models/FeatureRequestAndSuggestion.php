<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class FeatureRequestAndSuggestion extends Model
{
    use HasFactory, SoftDeletes;
    // protected $table = 'feature_request_and_suggestions';
    protected $fillable = [
        'user_id',
        'feature_name',
        'feature_description',
        'suggestion_title',
        'suggestion_description',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
