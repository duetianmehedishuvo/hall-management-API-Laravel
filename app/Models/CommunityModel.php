<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CommunityModel extends Model
{
    protected $table = 'community_table';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
