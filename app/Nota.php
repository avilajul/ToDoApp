<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Nota extends Model
{
    protected $fillable = ['id_usuario','id_categoria','descripcion'];

    public function categoria()
    {
        return $this->belongTo('App\Categoria');
    }
}
