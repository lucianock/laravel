<?php

use Illuminate\Routing\Router;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\DB;


Route::get('/datos', function () {
    $nombre = 'Luciano';
    $marcas = ['Toyota', 'Honda', 'Ford', 'Chevrolet'];

    return view('datos', ['marcas' => $marcas, 'nombre' => $nombre]);
});

Route::view('/formulario', 'formulario');

Route::post('/proceso', function () {
    $nombre = request('nombre');
    return view('proceso', ['nombre' => $nombre, 'apellido' => 'Perez']);
});

Route::view('/inicio', 'inicio');

Route::get('/lista-regiones', function () {
    $regiones = DB::table('regiones')->get();
    return view('lista-regiones', ['regiones' => $regiones]);
});

Route::get('/regiones', function () {
    //obtener las regiones
    $regiones = DB::table('regiones')->get();
    //dd(DB::table('regiones')->toSql());
    return view('regiones', ['regiones' => $regiones]);
});

Route::view('/region/create', 'regionCreate');

Route::post('/region/store', function () {
    try {
        $nombre = request('nombre');
        DB::table('regiones')->insert(['nombre' => $nombre]);
        return redirect('/regiones')->with(['mensaje' => 'Región creada exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/regiones')->with(['mensaje' => 'Hubo un problema al crear la región', 'css' => 'danger']);
    }
});

Route::get('/region/edit/{id}', function ($id) {
    $region = DB::table('regiones')->where('idRegion', $id)->first();

    return view('regionEdit', ['region' => $region]);
});

Route::post('/region/update', function () {
    try {
        $id = request('idRegion');
        $nombre = request('nombre');
        DB::table('regiones')->where('idRegion', $id)->update(['nombre' => $nombre]);
        return redirect('/regiones')->with(['mensaje' => 'Región actualizada exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/regiones')->with(['mensaje' => 'Hubo un problema al actualizar la región', 'css' => 'danger']);
    }
});

Route::get('/region/delete/{id}', function ($id) {
    $region = DB::table('regiones')->where('idRegion', $id)->first();

    $check = DB::table('destinos')->where('idRegion', $id)->count();
    if ($check > 0) {
        return redirect('/regiones')->with(['mensaje' => 'No se puede eliminar la región "'.$region->nombre.'" porque tiene destinos asociados', 'css' => 'warning']);
    } else {
        //DB::table('regiones')->where('idRegion', $id)->delete();
        return view('regionDelete', ['region' => $region]);
    }
});

Route::post('/region/destroy', function () {
    try {
        $id = request('idRegion');
        DB::table('regiones')->where('idRegion', $id)->delete();
        return redirect('/regiones')->with(['mensaje' => 'Región eliminada exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/regiones')->with(['mensaje' => 'Hubo un problema al eliminar la región', 'css' => 'danger']);
    }
});

Route::get('/destinos', function () {
    $destinos = DB::table('destinos')
        ->join('regiones', 'destinos.idRegion', '=', 'regiones.idRegion')
        ->select('destinos.*', 'regiones.nombre as nombre')
        ->get();
    return view('destinos', ['destinos' => $destinos]);
});

Route::get('/destino/create', function () {
    $regiones = DB::table('regiones')->get();
    return view('destinoCreate', ['regiones' => $regiones]);
});

Route::post('/destino/store', function () {
    try {
        $idRegion = request('idRegion');
        $aeropuerto = request('aeropuerto');
        $precio = request('precio');
        DB::table('destinos')->insert(['idRegion' => $idRegion, 'aeropuerto' => $aeropuerto, 'precio' => $precio, 'activo' => 1]);
        return redirect('/destinos')->with(['mensaje' => 'Destino creado exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/destinos')->with(['mensaje' => 'Hubo un problema al crear el destino.', 'css' => 'danger']);
    }
});

Route::get('/destino/edit/{id}', function ($id) {
    $destino = DB::table('destinos')->where('idDestino', $id)->first();
    $regiones = DB::table('regiones')->get();
    return view('destinoEdit', ['destino' => $destino, 'regiones' => $regiones]);
});

Route::post('/destino/update', function () {
    try {
        $id = request('idDestino');
        $aeropuerto = request('aeropuerto');
        $idRegion = request('idRegion');
        $precio = request('precio');
        DB::table('destinos')->where('idDestino', $id)->update(['aeropuerto' => $aeropuerto, 'idRegion' => $idRegion, 'precio' => $precio]);
        return redirect('/destinos')->with(['mensaje' => 'Destino actualizado exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/destinos')->with(['mensaje' => 'Hubo un problema al actualizar el destino: ' . $e->getMessage(), 'css' => 'danger']);
    }
});

Route::get('/destino/delete/{id}', function ($id) {
    $destino = DB::table('destinos')->where('idDestino', $id)->first();
    return view('destinoDelete', ['destino' => $destino]);
});

Route::post('/destino/destroy', function () {
    try {
        $id = request('idDestino');
        DB::table('destinos')->where('idDestino', $id)->delete();
        return redirect('/destinos')->with(['mensaje' => 'Destino eliminado exitosamente', 'css' => 'success']);
    } catch (\Exception $e) {
        return redirect('/destinos')->with(['mensaje' => 'Hubo un problema al eliminar el destino', 'css' => 'danger']);
    }
});

