<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Articulo extends Model
{
    protected $fillable = ['id_categoria','codigo','nombre','precio_venta','stock', 'descripcion','condicion'];

    public function categoria()
    {
        return $this->belongTo('App\Categoria');
    }
}