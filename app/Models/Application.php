<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Application extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'applications';

    // Primary key
    protected $primaryKey = 'application_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes
    protected $fillable = [
        'application_id',
        'user_id',
        'name',
        'user_type',
        'ic_number',
        'matrix_number',
        'phone',
        'email',
        'semester',
        'session',
        'rental_purpose',
        'check_in_date',
        'check_out_date',
        'num_participants',
        'male',
        'female',
        'package',
        'document_path',
        'application_status',
        'processed_by',
        'processed_at',
        'rejection_reason',
        'payment_amount',
        'payment_exception',
        'room_allocation',
        'disabled_male',
        'disabled_female',
    ];

    // Attribute casting
    protected $casts = [
        'check_in_date' => 'date',
        'check_out_date' => 'date',
        'processed_at' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    /**
     * Get the user who submitted the application.
     */
    public function user()
    {
        return $this->belongsTo(User::class, 'user_id')->withDefault();
    }

    /**
     * Get the admin who processed the application.
     */

    public function processedBy()
{
    return $this->belongsTo(\App\Models\Admin::class, 'processed_by', 'staff_id');
}

    /**
     * Get the package associated with this application.
     */
public function packageModel()
{
    return $this->belongsTo(Package::class, 'package', 'id')->withDefault();
}


    /**
     * Get the successful payment record associated with the application.
     */
    public function payment()
{
    return $this->hasOne(Payment::class, 'application_id', 'application_id')
                ->whereIn('payment_status', ['paid', 'successful'])
                ->latest('created_at');
}

    /**
     * Automatically notify user when application status is updated.
     */
    protected static function booted()
    {
        static::updated(function ($application) {
            if ($application->isDirty('application_status')) {
                $user = $application->user;
                if ($user) {
                    $user->notify(new \App\Notifications\ApplicationStatusUpdated($application));
                }
            }
        });
    }

}
