<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookPurchedModel extends Model
{
    protected $table = 'book_purched_table';
    protected $primaryKey = 'id';
    public $incrementing = true;
    protected $keyType = 'int';
    public $timestamps = true;
}
