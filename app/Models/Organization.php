<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Organization extends Model
{
    use HasFactory, SoftDeletes;

    protected $fillable = [
        'organization_name',
        'organization_logo',
        'gst_number',
        'address',
        'logo_request',
        'logo_created',
        'user_id',
        'account_holder_name',
        'account_number',
        'ifsc_code',
        'branch_name',
        'bank_name',
        'state',
        'qr_payment_img',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
