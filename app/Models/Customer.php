<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Casts\Attribute;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Customer extends Model
{
    use SoftDeletes;

    protected $table = 'customers';

    // Define the fillable fields for mass assignment
    protected $fillable = [
        'user_id',
        'name',
        'gender',
        'email',
        'country_code',
        'phone',
        'base_measurements',
        'address',
        'notes',
        'dob'
    ];

    protected $with = ['photos'];

    // Accessor for 'address'
    protected function address(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true)['value'] ?? '',
            // set: fn($value) => json_encode(['value' => $value])
        );
    }

    // Accessor for 'notes'
    protected function notes(): Attribute
    {
        return Attribute::make(
            get: fn($value) => json_decode($value, true),
            // set: fn($value) => json_encode($value)
        );
    }

    protected function name(): Attribute
    {
        return Attribute::make(
            get: fn(string $value) => ucfirst($value),
            set: fn(string $value) => strtolower($value),
        );
    }

    protected function gender(): Attribute
    {
        return Attribute::make(
            get: function ($value) {
                $return = "";
                if ($value === 'm') $return = "Male";
                if ($value === 'f') $return = "Female";
                if ($value === 'o') $return = "Other";
                return $return;
            }
        );
    }
    // ✅ Correct accessor name and spelling
    protected function baseMeasurements(): Attribute
    {
        return Attribute::make(
            get: function ($value, $attributes) {
                $decoded = json_decode($attributes['base_measurements'] ?? '{}', true);
                unset($decoded['unit']);
                return json_encode($decoded, true);
            }
        );
    }


    //    Relations
    public function photos()
    {
        return $this->hasMany(CustomerPhoto::class, 'customer_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }

    public function userCustomers()
    {
        return $this->hasMany(UserCustomer::class);
    }

    public function users()
    {
        return $this->belongsToMany(User::class, 'users_customers')
            ->withTimestamps();
    }


    //for Soft delete customer related photos
    protected static function booted()
    {
        static::deleting(function ($customer) {
            if ($customer->isForceDeleting()) {
                // Permanently delete related photos
                $customer->photos()->withTrashed()->forceDelete();
            } else {
                // Soft delete related photos
                $customer->photos()->delete();
            }
        });

        static::restoring(function ($customer) {
            $customer->photos()->withTrashed()->restore();
        });
    }
}
