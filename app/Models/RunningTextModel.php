<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RunningTextModel extends Model
{
    protected $table = 'runningtexttable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
