<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Support\Str; 

class Admin extends Authenticatable
{
    use Notifiable, HasFactory;

    protected $table = 'admin'; // Table name

    protected $primaryKey = 'staff_id'; // Custom primary key

    public $incrementing = false; // Important for non-integer IDs

    protected $keyType = 'string'; // Because staff_id is varchar

    protected $fillable = [
        'staff_id',
        'staff_name',
        'email',
        'password',
        'last_login_at',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'last_login_at' => 'datetime',
    ];

    /**
     * Automatically hash the password when it's set
     */
   public function setPasswordAttribute($value)
{
    // Avoid double-hashing if already hashed
    if (!empty($value) && !Str::startsWith($value, '$2y$')) {
        $this->attributes['password'] = bcrypt($value);
    } else {
        $this->attributes['password'] = $value;
    }
}

}
