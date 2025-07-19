<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserCustomer extends Model
{
    use HasFactory;
    protected $table = 'users_customers';

    protected $primaryKey = 'id';
    protected $fillable = [
        'user_id',
        'customer_id'
    ];

    //-- Relations

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function customer()
    {
        return $this->belongsTo(Customer::class);
    }
}
