<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ComplainModel extends Model
{
    
    protected $table = 'complaintbl';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
