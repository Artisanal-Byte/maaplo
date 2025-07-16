<?php

namespace App\Models;

// use Illuminate\Contracts\Auth\MustVerifyEmail;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use League\Flysystem\UnableToCreateDirectory;

class User extends Authenticatable
{
    /** @use HasFactory<\Database\Factories\UserFactory> */
    use HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var list<string>
     */
    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'subscription_plan_id',
        'validity',
        'password',
        'status',
        'avatar',
        'hash_organization',
    ];

    /**
     * The attributes that should be hidden for serialization.
     *
     * @var list<string>
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * Get the attributes that should be cast.
     *
     * @return array<string, string>
     */
    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    //-- Relations

    public function userCustomers()
    {
        return $this->hasMany(UserCustomer::class);
    }

    public function customers()
    {
        return $this->hasManyThrough(
            Customer::class,        // Final model
            UserCustomer::class,    // Intermediate model
            'user_id',              // Foreign key on UserCustomer table...
            'id',                   // Foreign key on Customer table (usually 'id')
            'id',                   // Local key on User table
            'customer_id'           // Local key on UserCustomer table
        );
    }


    // public function customers()
    // {
    //     return $this->hasMany(Customer::class);
    // }

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
    public function organization()
    {
        return $this->belongsTo(Organization::class);
    }
    protected $appends = ['formatted_validity'];
    public function getFormattedValidityAttribute()
    {
        return $this->validity ? Carbon::parse($this->validity)->format('d/m/Y') : null;
    }

    public function subscriptionPlan()
    {
        return $this->belongsTo(SubscriptionPlan::class, 'subscription_plan_id');
    }

    public function organizations()
    {
        return $this->belongsToMany(Organization::class);
    }
}
