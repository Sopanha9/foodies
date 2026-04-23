<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CustomerUser extends Model
{
    protected $table = 'users';

    protected $fillable = [
        'full_name',
        'email',
        'password_hash',
        'phone',
        'default_address',
        'is_active',
        'last_login_at',
    ];

    protected $casts = [
        'is_active' => 'boolean',
        'last_login_at' => 'datetime',
    ];

    protected $hidden = [
        'password_hash',
    ];

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class, 'user_id');
    }
}
