<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PhotoUploadModel extends Model
{
    protected $table = 'testimage';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
