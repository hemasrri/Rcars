<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
use App\Models\Semester;
use Illuminate\Support\Facades\Log;

class Payment extends Model
{
    use HasFactory;

    // Table name
    protected $table = 'payments';

    // Primary key
    protected $primaryKey = 'payment_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Mass assignable attributes (removed 'is_exception')
    protected $fillable = [
        'payment_id',
        'application_id',
        'amount',
        'payment_status',
        'payment_date',
        'semester',
        'session',
        'payment_datetime',
        'payment_method',
        'verified_by',
        'remarks',
        'transaction_id',
    ];

    // Attribute casting (removed 'is_exception')
    protected $casts = [
        'amount' => 'decimal:2',
        'payment_date' => 'date',
        'payment_datetime' => 'datetime',
        'created_at' => 'datetime',
        'updated_at' => 'datetime',
    ];

    // Validate enum values when setting status (removed 'exception')
    public function setPaymentStatusAttribute($value)
    {
        $validStatuses = ['pending', 'paid'];

        if (!in_array($value, $validStatuses)) {
            Log::warning("Invalid payment status attempted: " . $value);
            throw new \InvalidArgumentException("Invalid payment status: $value");
        }

        $this->attributes['payment_status'] = $value;
    }

    /**
     * Get the application associated with this payment.
     */
    public function application()
    {
        return $this->belongsTo(Application::class, 'application_id', 'application_id')->withDefault();
    }

    /**
     * Get the semester record related to this payment.
     */
    public function semesterRecord()
    {
        return $this->hasOne(Semester::class, 'semester', 'semester')
                    ->where('session', $this->session);
    }

    /**
     * Notify user on successful payment creation.
     */
    protected static function booted()
    {
        static::created(function ($payment) {
            $user = $payment->application->user ?? null;
            if ($user) {
                $user->notify(new \App\Notifications\PaymentReceived($payment));
            }
        });
    }

    public function semester()
    {
        return $this->belongsTo(Semester::class);
    }
}
