<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Payment;

class Semester extends Model
{
    use HasFactory;

    protected $table = 'semesters';

    protected $primaryKey = 'id';

    // Assuming 'id' is auto-incrementing integer (default)
    public $incrementing = true;
    protected $keyType = 'int';

    protected $fillable = [
        'session',
        'semester',
        'start_date',
        'end_date',
    ];

    protected $casts = [
        'start_date' => 'date',
        'end_date' => 'date',
    ];

    /**
     * Get the combined session and semester as "session/semester" format.
     */
    public function getCombinedAttribute()
    {
        return $this->session . '/' . $this->semester;
    }

    /**
     * Get all payments related to this semester.
     * Assuming matching both session and semester fields in payments table.
     */
    public function payments()
    {
        return $this->hasMany(Payment::class, 'semester', 'semester')
                    ->where('session', $this->session);
    }
}
