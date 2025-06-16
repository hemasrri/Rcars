<?php

namespace App\Models;

use Illuminate\Foundation\Auth\User as Authenticatable; // Import the Authenticatable class
use Illuminate\Notifications\Notifiable;

class UthmUser  extends Authenticatable // Extend the Authenticatable class
{
    use Notifiable;

    // Define the table name if it does not follow Laravel's conventions
    protected $table = 'users'; // Adjust to the unified table name

    // Define the primary key if it's not 'id'
    protected $primaryKey = 'user_id'; // Adjust if your primary key is different

    // If your primary key is not an incrementing integer
    public $incrementing = false; // Set to true if it is an incrementing integer
    protected $keyType = 'string'; // Set to 'int' if it's an integer

    // Define any fillable fields
    protected $fillable = [
        'name',
        'user_id',
        'ic_number', // Include ic_number if it's part of the unified table
        'user_type',
        'email',
        'phone',
        'password',
    ];

    // Optionally, you can define hidden fields
    protected $hidden = [
        'password', // Hide the password field when serializing the model
        'remember_token', // If you are using "remember me" functionality
    ];

    // Optionally, you can define casts for attributes
    protected $casts = [
        'email_verified_at' => 'datetime', // If you have email verification
    ];
}