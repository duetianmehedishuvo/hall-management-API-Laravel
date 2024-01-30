<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmergencyModel extends Model
{
    protected $table = 'emergencytable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
