<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class access_meli extends Model
{
    protected $table = "access_meli";
    public $timestamps = false;
    protected $primaryKey = "id";
    use HasFactory;
}
