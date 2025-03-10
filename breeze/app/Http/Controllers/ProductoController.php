<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Marca;
use App\Models\Producto;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\View\View;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index() : View
    {
        //obtenemos listado de productos
        /*
        * $productos = Producto::join('marcas', 'productos.idMarca', '=', 'marcas.idMarca')->paginate(5);
        * */
        $productos = Producto::with([ 'getMarca', 'getCate' ])->paginate(5);

        return view('productos', [ 'productos'=>$productos ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create() : View
    {
        //Obtenemos listados de marcas y de categorías
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('productoCreate',
                    [
                        'marcas'=>$marcas,
                        'categorias'=>$categorias
                    ]
                );
    }

    private function validarForm( Request $request, $id = null ) : void
    {
        $request->validate(
            [
                'prdNombre'=>'required|unique:productos,prdNombre,'.$id.',idProducto|min:2|max:75',
                'prdPrecio'=>'required|numeric|min:0',
                'idMarca'=>'required|exists:marcas,idMarca',
                'idCategoria'=>'required|exists:categorias,idCategoria',
                'prdDescripcion'=>'max:1000',
                'prdImagen'=>'mimes:jpg,jpeg,png,gif,svg,webp|max:1024'
            ],
            [
                'prdNombre.required'=>'El campo "Nombre del producto" es obligatorio.',
                'prdNombre.unique'=>'El "Nombre del producto" ya existe.',
                'prdNombre.min'=>'El campo "Nombre del producto" debe tener como mínimo 2 caractéres.',
                'prdNombre.max'=>'El campo "Nombre" debe tener 75 caractéres como máximo.',
                'prdPrecio.required'=>'Complete el campo Precio.',
                'prdPrecio.numeric'=>'Complete el campo Precio con un número.',
                'prdPrecio.min'=>'Complete el campo Precio con un número mayor a 0.',
                'idMarca.required'=>'Seleccione una marca.',
                'idMarca.exists'=>'Seleccione una marca existente',
                'idCategoria.required'=>'Seleccione una categoría.',
                'idCategoria.exists'=>'Seleccione una categoría existente',
                'prdDescripcion.max'=>'Complete el campo Descripción con 1000 caractéres como máxino.',
                'prdImagen.mimes'=>'Debe ser una imagen.',
                'prdImagen.max'=>'Debe ser una imagen de 1MB como máximo.'
            ]
        );
    }

    private function subirImagen( Request $request ) : string
    {
        //Si no enviaron una imagen store()
        $prdImagen = 'noDisponible.svg';

        //Si enviaron una imagen
        if( $request->file('prdImagen') ){
            $file = $request->file('prdImagen');
            /* renombramos archivo */
            $time = time();
            $extension = $file->getClientOriginalExtension();
            $prdImagen = $time.'.'.$extension;
            /* subimos archivo */
            $file->move( public_path('/imgs'), $prdImagen );
        }
        return $prdImagen;
    }
    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request) : RedirectResponse
    {
        //validación
        $this->validarForm($request);
        $prdNombre = $request->prdNombre;
        //subida de archivo *
        $prdImagen = $this->subirImagen( $request );
        try {
            $producto = new Producto;
            //Asignamos atributos
            $producto->prdNombre = $prdNombre;
            $producto->prdPrecio = $request->prdPrecio;
            $producto->idMarca = $request->idMarca;
            $producto->idCategoria = $request->idCategoria;
            $producto->prdDescripcion = $request->prdDescripcion;
            $producto->prdImagen = $prdImagen;
            //Almacenamos en tabla productos
            $producto->save();
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'Producto: '.$prdNombre.' agregado correctamente',
                        'css'=>'success'
                    ]
                );
        }catch ( \Throwable $th ){
            //log  $th->getMessage()
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'No se pudo agregar el producto: '.$prdNombre.'.',
                        'css'=>'danger'
                    ]
                );
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Producto $producto) : View
    {
        //Obtenemos listados de marcas y de categorías
        $marcas = Marca::all();
        $categorias = Categoria::all();
        return view('productoEdit',
                    [
                        'producto' => $producto,
                        'marcas' => $marcas,
                        'categorias' => $categorias
                    ]
                );
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Producto $producto)
    {
    
        //validación
        $this->validarForm($request, $producto->idProducto);
        $prdNombre = $request->prdNombre;
        //subida de archivo *
        $prdImagen = $this->subirImagen( $request );
        try {
            //Asignamos atributos
            $producto->prdNombre = $prdNombre;
            $producto->prdPrecio = $request->prdPrecio;
            $producto->idMarca = $request->idMarca;
            $producto->idCategoria = $request->idCategoria;
            $producto->prdDescripcion = $request->prdDescripcion;
            $producto->prdImagen = $prdImagen;
            //Almacenamos en tabla productos
            $producto->save();
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'Producto: '.$prdNombre.' modificado correctamente',
                        'css'=>'success'
                    ]
                );
        }catch ( \Throwable $th ){
            //log  $th->getMessage()
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'No se pudo modificar el producto: '.$prdNombre.'.',
                        'css'=>'danger'
                    ]
                );
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function delete(string $id): RedirectResponse | View
    {
        //obtenemos datos de un producto por su id
        $producto = Producto::find($id);
        // retornamos vista de confirmación de baja
        return view('productoDelete', ['producto' => $producto]);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Producto $producto)
    {
        $prdNombre = $producto->prdNombre;
        try {
            $producto->delete();
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'Producto: '.$prdNombre.' eliminado correctamente',
                        'css'=>'success'
                    ]
                );
        }catch ( \Throwable $th ){
            //log  $th->getMessage()
            return redirect()->route('productos')
                ->with(
                    [
                        'mensaje'=>'No se pudo eliminar el producto: '.$prdNombre.'.',
                        'css'=>'danger'
                    ]
                );
        }
    }
}