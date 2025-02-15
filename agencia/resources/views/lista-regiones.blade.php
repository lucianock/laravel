@extends('layouts.plantilla')

@section('title', 'Listado de regiones')

@section('contenido')
    <h1>Listado de regiones</h1>
    @foreach ($regiones as $region)
        <ul>
            <li>{{ $region->idRegion }} - {{ $region->nombre }}</li>
        </ul>
    @endforeach
@endsection
