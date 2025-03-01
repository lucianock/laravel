<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    //use HasFactory;
    protected $primaryKey = 'idCategoria';
    protected $fillable = ['catNombre'];
    public $timestamps = false;
}
