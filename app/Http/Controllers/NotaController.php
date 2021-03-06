<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Nota;

class NotaController extends Controller
{
    public function index(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }

            $notas = nota::join('categorias', 'notas.id_categoria', '=', 'categorias.id')       
                ->select('notas.id', 'notas.id_categoria', 'notas.descripcion', 'categorias.nombre as nombre_categoria')        
                ->orderBy('notas.id','desc')->paginate(10);       

        return [
            'pagination' => [
                'total'         => $notas->total(),
                'current_page'  => $notas->currentPage(),
                'per_page'      => $notas->perPage(),
                'last_page'     => $notas->lastPage(),
                'from'          => $notas->firstItem(),
                'to'            => $notas->lastItem(),
            ],
            'notas' => $notas
        ];
    }
    
    public function listarNota(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }

        $buscar = $request->buscarC;
        $criterio = $request->criterio;

        if($buscar == '')
        {
            $notas = Nota::join('categorias', 'notas.id_categoria', '=', 'categorias.id')
            ->select('notas.id', 'notas.id_categoria', 'notas.descripcion', 'categorias.nombre as nombre_categoria')
            ->orderBy('notas.id','desc')->paginate(10);
        }
        else
        {
            $notas = nota::join('categorias', 'notas.id_categoria', '=', 'categorias.id')
            ->select('notas.id', 'notas.id_categoria', 'notas.descripcion', 'categorias.nombre as nombre_categoria')
            ->where('notas.',$criterio,'like', '%'.$buscar.'%')
            ->orderBy('notas.id','desc')->paginate(10);
        }
        
        return ['notas' => $notas];
    }

    public function buscarNota(Request $request)
    {
        if(!$request->ajax()) return redirect('/');

        $buscar = $request->buscarC;

        $notas = nota::join('categorias', 'notas.id_categoria', '=', 'categorias.id')
        ->select('notas.id', 'notas.id_categoria', 'notas.descripcion', 'categorias.nombre as nombre_categoria')
        ->where('categorias.id','=', $buscar)
        ->orderBy('notas.id','desc')->paginate(10);

        return [
            'pagination' => [
                'total'         => $notas->total(),
                'current_page'  => $notas->currentPage(),
                'per_page'      => $notas->perPage(),
                'last_page'     => $notas->lastPage(),
                'from'          => $notas->firstItem(),
                'to'            => $notas->lastItem(),
            ],
            'notas' => $notas
        ];
    }

    public function store(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
        $nota = new Nota();
        $nota->id_categoria  = $request->id_categoria;
        $nota->descripcion = $request->descripcion;
        $nota->save();
    }

    public function modificarNota(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
        $nota = Nota::findOrFail($request->id);
        $nota->id_categoria  = $request->id_categoria;
        $nota->descripcion = $request->descripcion;

        $nota->save();
    }

    public function borrarNota(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
        $nota = Nota::findOrFail($request->id);
        $nota->delete();
    }
}