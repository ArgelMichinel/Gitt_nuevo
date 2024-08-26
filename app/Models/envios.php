<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class envios extends Model
{
    protected $table = "envios";
    public $timestamps = false;
    protected $primaryKey = "id";
    use HasFactory;
}
