<?php
declare(strict_types=1);

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PointModel extends Model
{
    protected $table = 'points';

    protected $fillable = [
        'id',
        'uuid',
        'name',
        'latitude',
        'longitude',
        'open_hour',
        'close_hour'
    ];
}
