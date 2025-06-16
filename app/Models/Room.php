<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Block;
use App\Models\Hostel;

class Room extends Model
{
    use HasFactory;

    protected $table = 'rooms';
    protected $primaryKey = 'room_id';
    public $incrementing = false;
    protected $keyType = 'string';

    protected $fillable = [
        'room_id',
        'block_id',
        'room_number',
        'floor_number',
        'capacity',
        'room_status',
        'gender',
        'hostel_id', // added
    ];

    public function block()
    {
        return $this->belongsTo(Block::class, 'block_id', 'block_id');
    }

    public function hostel()
    {
        return $this->belongsTo(Hostel::class, 'hostel_id', 'hostel_id');
    }
}
