<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Hostel extends Model
{
    use HasFactory;

    protected $table = 'hostels'; 
    protected $primaryKey = 'hostel_id'; 
    public $incrementing = false; // Custom ID (non-incrementing)
    protected $keyType = 'string'; // The ID is a string (varchar)

    protected $fillable = [
        'hostel_id',
        'hostel_name',
        'gender',
        'total_blocks',
        'total_rooms',
        'image',
        'facilities',
    ];

    /**
     * A hostel has many blocks.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function blocks()
    {
        return $this->hasMany(Block::class, 'hostel_id', 'hostel_id');
    }

    /**
     * A hostel has many rooms (direct relationship via block_id).
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasManyThrough
     */
    public function rooms()
    {
        return $this->hasManyThrough(Room::class, Block::class, 'hostel_id', 'block_id', 'hostel_id', 'block_id');
    }
}
