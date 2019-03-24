<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request; 
use App\Categoria;

class CategoriaController extends Controller
{
    public function buscarCategoria(Request $request)
    {
/*         if(!$request->ajax())
        {
            return redirect('/');
        } */
        $sugerencia = $request->get('sugerencia');

        $categorias = Categoria::where('nombre', 'like', '%'.$sugerencia.'%')
        ->select('id','nombre')->orderBy('nombre', 'asc')->get();

         return ['categorias' => $categorias]; 
    }

}
