<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProviderModel extends Model
{
    protected $table = 'provider';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
