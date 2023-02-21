<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RoomModels extends Model
{
    protected $table = 'roomtable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
