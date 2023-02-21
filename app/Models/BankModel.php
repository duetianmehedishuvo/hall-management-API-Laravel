<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BankModel extends Model
{
    protected $table = 'bankinformationtable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
