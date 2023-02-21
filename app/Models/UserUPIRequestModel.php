<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserUPIRequestModel extends Model
{
    protected $table = 'userupirequestreport';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
