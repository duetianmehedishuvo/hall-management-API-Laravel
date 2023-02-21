<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MeterModel extends Model
{
    protected $table = 'metertable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
