<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallFeeModel extends Model
{
    protected $table = 'hallfeetbl';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
