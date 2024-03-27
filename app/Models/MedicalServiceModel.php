<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalServiceModel extends Model
{
    protected $table = 'medical_service_table';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
