<?php

namespace App\Http\Controllers;

use App\User;
use App\Persona;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }

        $buscar = $request->buscar;
        $criterio = $request->criterio;

        if($buscar == '')
        {
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
            ->join('roles', 'users.id_rol', '=', 'roles.id')
            ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
            'personas.num_documento', 'personas.direccion', 'personas.telefono',
            'personas.email', 'users.usuario', 'users.password', 'users.condicion',
            'users.id_rol', 'roles.nombre as rol')
            ->orderBy('personas.id', 'desc')->paginate(3);
        }
        else
        {
            $personas = User::join('personas', 'users.id', '=', 'personas.id')
            ->join('roles', 'users.id_rol', '=', 'roles.id')
            ->select('personas.id', 'personas.nombre', 'personas.tipo_documento',
            'personas.num_documento', 'personas.direccion', 'personas.telefono',
            'personas.email', 'users.usuario', 'users.password', 'users.condicion',
            'users.id_rol', 'roles.nombre as rol')
            ->where('personas.'.$criterio,'like', '%'.$buscar.'%')
            ->orderBy('personas.id','desc')->paginate('3');
        }
        
        return [
            'pagination' => [
                'total'         => $personas->total(),
                'current_page'  => $personas->currentPage(),
                'per_page'      => $personas->perPage(),
                'last_page'     => $personas->lastPage(),
                'from'          => $personas->firstItem(),
                'to'            => $personas->lastItem(),
            ],
            'personas' => $personas
        ];
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
 
        try
        {
            DB::beginTransaction();
            $persona = new Persona();
            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;    
            $persona->save();

            $user = new User();
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->id_rol = $request->id_rol;

            $user->id = $persona->id;
            $user->save();

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
 
        try
        {
            DB::beginTransaction();

            //Buscar primero el proovedor a modificar
            $user = User::findOrFail($request->id);
            $persona = Persona::findOrFail($user->id);

            $persona->nombre = $request->nombre;
            $persona->tipo_documento = $request->tipo_documento;
            $persona->num_documento = $request->num_documento;
            $persona->direccion = $request->direccion;
            $persona->telefono = $request->telefono;
            $persona->email = $request->email;    
            $persona->save();
            
            $user->usuario = $request->usuario;
            $user->password = bcrypt($request->password);
            $user->condicion = '1';
            $user->id_rol = $request->id_rol;
            $user->save();

            DB::commit();
        }
        catch(Exception $e)
        {
            DB::rollback();
        }
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function activar(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
        $user = User::findOrFail($request->id);        
        $user->condicion = '1';
        $user->save();
    }
        /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function desactivar(Request $request)
    {
        if(!$request->ajax())
        {
            return redirect('/');
        }
        $user = User::findOrFail($request->id);        
        $user->condicion = '0';
        $user->save();
    }
}
