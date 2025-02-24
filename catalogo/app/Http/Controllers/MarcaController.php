<?php

namespace App\Http\Controllers;

use App\Models\Marca;
use App\Models\Producto;
use Illuminate\View\View;
use Illuminate\Http\Request;
use Illuminate\Http\RedirectResponse;

class MarcaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        //obtenemos listado de regiones
        $marcas = Marca::paginate(4);
        return view('marcas', [ 'marcas'=>$marcas ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        return view('marcaCreate');
    }

    private function validarForm( Request $request )
    {
        $request->validate(
            //[ 'campo'=>regla1|regla2 ]
            [ 'mkNombre'=>'required|unique:marcas|min:2|max:30' ],
            [
                'mkNombre.required'=>'El campo "Nombre de la marca" es obligatorio.',
                'mkNombre.unique'=>'No puede haber dos marcas con el mismo nombre.',
                'mkNombre.min'=>'El campo "Nombre de la marca" debe tener al menos 2 caractéres.',
                'mkNombre.max'=>'El campo "Nombre de la marca" debe tener 30 caractéres como máximo.'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store( Request $request ) : RedirectResponse
    {
        // validación
        $this->validarForm( $request );
        $mkNombre = $request->mkNombre;
        try {
            //instanciamos
            $marca = new Marca;
            //asignamos atributos
            $marca->mkNombre = $mkNombre;
            //almacenamos en la tabla marcas
            $marca->save();
            return  redirect('/marcas')
                        ->with([
                                'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.',
                                'css'=>'success'
                        ]);

        }catch( Throwable $th )
        {
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'No se pudo agregar la marca: '.$mkNombre.'.',
                    'css'=>'danger'
                ]);
        }

        return $mkNombre;
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit( string $id ) : View
    {
        //obtenemos datos de una marca por su id
        //DB::table('marcas')->where()->first();
        $marca = Marca::find($id);
        return  view( 'marcaEdit', [ 'marca'=>$marca ] );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request) : RedirectResponse
    {
        $mkNombre = $request->mkNombre;
        $this->validarForm($request);
        try {
            //obtenemos datos de una marca por su id
            $marca = Marca::find($request->idMarca);
            //reasignamos valores de atributos
            $marca->mkNombre = $mkNombre;
            // almacenar en tabla de marcas
            $marca->save();
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.',
                    'css'=>'success'
                ]);
        }catch( Throwable $th )
        {
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'No se pudo modificar la marca: '.$mkNombre.'.',
                    'css'=>'danger'
                ]);
        }

    }

    public function delete( string $id ) : RedirectResponse | View
    {
        //obtenemos datos de una marca por su id
        $marca = Marca::find($id);
        ### si hay productos relacionados a esa marca
        if( Producto::checkProductoPorMarca( $id ) ){
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'No se puede eliminar la marca: '.$marca->mkNombre.' porque tiene productos relacionados.',
                    'css'=>'warning'
                ]);
        }
        // retornamos vista de confirmación de baja
        return view('marcaDelete', [ 'marca'=>$marca ]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy( Request $request ) : RedirectResponse
    {
        $mkNombre = $request->mkNombre;
        try {
            /* $marca = Marca::find($request->idMarca);
               $marca->delete(); */
            Marca::destroy($request->idMarca);
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'Marca: '.$mkNombre.' agregada correctamente.',
                    'css'=>'success'
                ]);
        }
        catch ( \Throwable $th ){
            return  redirect('/marcas')
                ->with([
                    'mensaje'=>'No se pudo eliminar la marca: '.$mkNombre.'.',
                    'css'=>'warning'
                ]);
        }

    }
}
