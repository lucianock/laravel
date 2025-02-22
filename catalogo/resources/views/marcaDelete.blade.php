@extends('layouts.plantilla')
@section('contenido')

    <h1>Eliminación de una marca</h1>

    <div class="alert p-4 col-8 mx-auto shadow">
        <form action="/marca/delete" method="post">
        @csrf
        @method('delete')
            <div class="form-group">
                <label for="mkNombre">Nombre de la Marca</label>
                <input type="text" name="mkNombre"
                       value="{{ $marca->mkNombre }}"
                       class="form-control" id="mkNombre" readonly>
            </div>
            <input type="hidden" name="idMarca"
                   value="{{ $marca->idMarca }}">

            <button class="btn btn-danger my-3 px-5">Eliminar marca</button>
            <a href="/marcas" class="btn btn-outline-secondary sep">
                Volver a panel de marcas
            </a>
        </form>
    </div>

    @if( $errors->any() )
        <div class="alert alert-danger p-4 col-8 mx-auto">
            <ul>
                @foreach( $errors->all() as $error )
                    <li>
                        <i class="bi bi-exclamation-triangle"></i>
                        {{ $error }}
                    </li>
                @endforeach
            </ul>
        </div>
    @endif

@endsection
