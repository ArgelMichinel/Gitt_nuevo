<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class listas extends Model
{
    protected $table = "listas";
    public $timestamps = false;
    protected $primaryKey = "id_list";
    use HasFactory;

    protected $fillable = [
        'options->enabled',
    ];
}
