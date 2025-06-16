<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use App\Models\Application;
class Package extends Model
{
    use HasFactory;

    protected $table = 'packages';

    // Since you're using a custom string ID (e.g., PCK001)
    protected $primaryKey = 'id';

    // VERY IMPORTANT: Set to false because 'id' is not auto-incrementing
    public $incrementing = false;

    // Also specify the key type is string
    protected $keyType = 'string';

    protected $casts = [
    'package' => 'string',
];

    protected $fillable = [
        'id', // include id here since you're generating it manually
        'package_name',
        'details',
        'category',
        'price_per_day',
    ];

    public function applications()
    {
return $this->hasMany(Application::class, 'package', 'id');
    }
}
