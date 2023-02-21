<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MealModel extends Model
{
    protected $table = 'mealtable';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
