<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class access_meli extends Model
{
    protected $table = "access_meli";
    public $timestamps = false;
    protected $primaryKey = "id";
    protected $fillable = [
        'id', // Agrega 'id' aquí
        'user_id', 
        'access_token', 
        'refresh_token', 
        'fec_hora',
        'Nombre'
    ];
    use HasFactory;
}
