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
    public function index(): View
    {
        //obtenemos listado de regiones
        $marcas = Marca::paginate(4);
        return view('marcas', ['marcas' => $marcas]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        return view('marcaCreate');
    }

    private function validarForm(Request $request)
    {
        $request->validate(
            //[ 'campo'=>regla1|regla2 ]
            ['mkNombre' => 'required|unique:marcas|min:2|max:30'],
            [
                'mkNombre.required' => 'El campo "Nombre de la marca" es obligatorio.',
                'mkNombre.unique' => 'No puede haber dos marcas con el mismo nombre.',
                'mkNombre.min' => 'El campo "Nombre de la marca" debe tener al menos 2 caractéres.',
                'mkNombre.max' => 'El campo "Nombre de la marca" debe tener 30 caractéres como máximo.'
            ]
        );
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request): RedirectResponse
    {
        // validación
        $this->validarForm($request);
        $mkNombre = $request->mkNombre;
        try {
            //instanciamos
            $marca = new Marca;
            //asignamos atributos
            $marca->mkNombre = $mkNombre;
            //almacenamos en la tabla marcas
            $marca->save();
            return redirect(route("marcas"))
                ->with([
                    'mensaje' => 'Marca: ' . $mkNombre . ' agregada correctamente.',
                    'css' => 'success'
                ]);
        } catch (Throwable $th) {
            return redirect(route("marcas"))
                ->with([
                    'mensaje' => 'No se pudo agregar la marca: ' . $mkNombre . '.',
                    'css' => 'danger'
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
    public function edit(string $id): View
    {
        //obtenemos datos de una marca por su id
        //DB::table('marcas')->where()->first();
        $marca = Marca::find($id);
        return  view('marcaEdit', ['marca' => $marca]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Marca $marca): RedirectResponse
    {
        $mkNombre = $request->mkNombre;
        $this->validarForm($request);
        try {
            // Asignar valores al modelo de Marca que se obtuvo automáticamente por la ruta
            $marca->mkNombre = $mkNombre;
            $marca->save();

            return redirect(route("marcas"))->with([
                'mensaje' => 'Marca: ' . $mkNombre . ' modificada correctamente.',
                'css' => 'success'
            ]);
        } catch (Throwable $th) {
            return redirect(route("marcas"))->with([
                'mensaje' => 'No se pudo modificar la marca: ' . $mkNombre . '.',
                'css' => 'danger'
            ]);
        }
    }

    public function delete(string $id): RedirectResponse | View
    {
        //obtenemos datos de una marca por su id
        $marca = Marca::find($id);
        ### si hay productos relacionados a esa marca
        if (Producto::checkProductoPorMarca($id)) {
            return  redirect(route("marcas"))
                ->with([
                    'mensaje' => 'No se puede eliminar la marca: ' . $marca->mkNombre . ' porque tiene productos relacionados.',
                    'css' => 'warning'
                ]);
        }
        // retornamos vista de confirmación de baja
        return view('marcaDelete', ['marca' => $marca]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($idMarca): RedirectResponse
    {
        try {
            // Buscar la marca por ID
            $marca = Marca::findOrFail($idMarca);

            // Obtener el nombre para el mensaje de éxito
            $mkNombre = $marca->mkNombre;

            // Eliminar la marca
            $marca->delete();

            // Redirigir con un mensaje de éxito
            return redirect(route("marcas"))
                ->with([
                    'mensaje' => 'La marca "' . $mkNombre . '" fue eliminada correctamente.',
                    'css' => 'success'
                ]);
        } catch (\Throwable $th) {
            // Si ocurre un error, redirigir con mensaje de advertencia
            return redirect(route("marcas"))
                ->with([
                    'mensaje' => 'No se pudo eliminar la marca.',
                    'css' => 'warning'
                ]);
        }
    }
}
