<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OtherModel extends Model
{
    protected $table = 'othertable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
