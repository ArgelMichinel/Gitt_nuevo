<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class access_nube extends Model
{
    protected $table = "access_nube";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $fillable = [
        'id', // Agrega 'id' aquí
        'user_id', 
        'access_token', 
        'fec_hora',
        'Nombre',
        'alcance'
    ];
    use HasFactory;
}
