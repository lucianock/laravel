<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Marca extends Model
{
    protected $primaryKey = 'idMarca';
    protected $fillable = ['mkNombre'];
    public $timestamps = false;
}
