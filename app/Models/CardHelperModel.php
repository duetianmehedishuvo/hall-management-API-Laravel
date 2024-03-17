<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CardHelperModel extends Model
{
    protected $table = 'card_helper_table';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = false;
}
