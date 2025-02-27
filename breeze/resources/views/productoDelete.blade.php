<x-app-layout>
    <x-slot name="header">
        <h2 class="font-semibold text-xl text-gray-400 leading-tight">
            {{ __('Productos') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-7xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden shadow-sm sm:rounded-lg">
                <div class="p-6 border-b border-gray-200">

                    <h1 class="pb-4 text-gray-300">Eliminación de un producto</h1>
                    <!-- formulario -->
                    <div class="shadow-md rounded-md max-w-3xl mb-72">
                        <form action="{{ route('producto.destroy', $producto->idProducto) }}" method="post">
                            @csrf
                            @method('DELETE')
                            <div class="p-6">
                                <label for="prdNombre" class="block text-sm font-medium text-gray-400">
                                    Nombre del producto
                                </label>
                                <div class="mt-1 flex rounded-md shadow-sm">
                                    <input type="text" name="prdNombre"
                                        value="{{ old('prdNombre', $producto->prdNombre) }}" id="prdNombre"
                                        class="py-2 px-3 bg-gray-800 text-green-400 text-2xl focus:ring-green-300 focus:border-green-300 flex-1 block w-full rounded-md border-gray-600"
                                        disabled>
                                </div>

                                <div class="py-6 flex items-center justify-end">

                                    <button
                                        class="text-red-500 hover:text-red-400
                                        bg-red-950 hover:bg-red-900 px-5 py-1 mr-6
                                        border border-red-500 rounded
                                        ">Eliminar
                                        producto</button>
                                    <a href="/productos"
                                        class="text-gray-400 hover:text-green-300
                                        bg-gray-900 hover:bg-gray-800 px-5 py-1
                                        border border-gray-500 rounded
                                        ">Volver
                                        a panel de productos</a>

                                </div>

                            </div>
                        </form>
                    </div>
                    <!-- FIN formulario -->


                </div>
            </div>
        </div>
    </div>
</x-app-layout>
