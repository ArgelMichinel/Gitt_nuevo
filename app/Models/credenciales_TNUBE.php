<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credenciales_TNUBE extends Model
{
    protected $table = "credenciales_TNUBE";
    public $timestamps = false;
    protected $primaryKey = "id";
    use HasFactory;
}
