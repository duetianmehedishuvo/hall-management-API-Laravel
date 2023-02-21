<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserMobileRequestComplainModels extends Model
{
    protected $table = 'usermobilerequestcomplain';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
