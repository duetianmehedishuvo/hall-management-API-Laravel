<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommendModel extends Model
{
    protected $table = 'commend_table';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
