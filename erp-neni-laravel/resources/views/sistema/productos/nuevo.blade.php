@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de producto</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese la información del producto</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="¡GUARDADO!">
                    El producto se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('productos.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            @include('partials.input', [
                                'name' => 'catalogo',
                                'placeholder' => 'Catalogo / Modelo / Nombre',
                                'icon' => 'fas fa-tag',
                                'label' => 'Catalogo / Modelo / Nombre',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.textarea', [
                                'name' => 'descripcion',
                                'label' => 'Ingrese el domicilio...',
                                'placeholder' => 'Ingrese la descripcion del producto...',
                                'rows' => 3,
                                'icon' => 'fas fa-bars',
                            ])
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            @include('partials.input', [
                                'name' => 'precio',
                                'placeholder' => 'Precio del producto con (Maximo 2 decimales)',
                                'icon' => 'fas fa-solid fa-dollar-sign',
                                'label' => 'Precio del producto (Maximo 2 decimales)',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'costo',
                                'placeholder' => 'Costo del producto con (Maximo 2 decimales)',
                                'icon' => 'fas fa-file-invoice-dollar',
                                'label' => 'Costo del producto (Maximo 2 decimales)',
                                'label-class' => 'text-lightblue',
                            ])
                        </div>
                    </div>
                    <!-- Contenedor del botonera -->
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            @include('partials.button', [
                                'type' => 'submit',
                                'label' => 'Guardar',
                                'theme' => 'primary',
                                'icon' => 'fas fa-save',
                            ])
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
