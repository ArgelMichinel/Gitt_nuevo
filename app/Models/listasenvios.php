<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listasenvios extends Model
{
    protected $table = "listasenvios";
    public $timestamps = false;
    use HasFactory;

    protected $fillable = [
        'options->enabled',
    ];
}
