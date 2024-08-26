<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class credenciales_MELI extends Model
{
    protected $table = "credenciales_MELI";
    public $timestamps = false;
    protected $primaryKey = "id";
    use HasFactory;
}
