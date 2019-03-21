<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Categoria extends Model
{
    protected $fillable = ['nombre','descripcion','condicion'];

    public function notas()
    {
        return $this->hasMany('App\Nota');
    }
}
