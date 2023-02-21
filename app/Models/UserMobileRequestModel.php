<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMobileRequestModel extends Model
{
    protected $table = 'usermobilerequestreport';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
