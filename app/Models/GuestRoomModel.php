<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class GuestRoomModel extends Model
{
    protected $table = 'guestroomtable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
