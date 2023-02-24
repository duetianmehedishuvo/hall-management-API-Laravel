<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallFeeDetailsModel extends Model
{
    protected $table = 'hallfeedetailstbl';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
