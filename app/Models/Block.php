<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Block extends Model
{
    use HasFactory;

    // Set the primary key and define it as non-auto-incrementing string
    protected $primaryKey = 'block_id';
    public $incrementing = false;
    protected $keyType = 'string';

    // Enable or disable timestamp management depending on your database schema
    public $timestamps = true; // Ensure you have created_at and updated_at columns in the table

    // Fillable attributes for mass assignment
    protected $fillable = [
        'block_id',
        'hostel_id',
        'block_name',
        'gender',
        'total_rooms',
        'total_floors',
    ];

    /**
     * A block belongs to a hostel.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'hostel_id');
    }

    
    /**
     * A block has many rooms.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function rooms()
    {
        return $this->hasMany(Room::class, 'block_id', 'block_id');
    }

    /**
     * Get statistics for the rooms in the block (available, occupied, maintenance).
     *
     * @return array
     */
    public function roomStats()
    {
        return [
            'available' => $this->rooms()->where('room_status', 'available')->count(),
            'occupied' => $this->rooms()->where('room_status', 'occupied')->count(),
            'maintenance' => $this->rooms()->where('room_status', 'maintenance')->count(),
        ];
    }
}
