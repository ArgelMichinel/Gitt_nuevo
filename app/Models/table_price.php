<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class table_price extends Model
{
    protected $table = "table_price";
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'options->enabled',
    ];
}
