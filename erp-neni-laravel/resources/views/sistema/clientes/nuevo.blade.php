@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de cliente</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese la informacion del cliente</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="Guardado">
                    El cliente se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('clientes.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            @include('partials.input', [
                                'name' => 'name',
                                'label' => 'Nombre Completo',
                                'placeholder' => 'Nombre Completo',
                                'icon' => 'fas fa-user',
                            ])
                            @include('partials.input', [
                                'name' => 'email',
                                'type' => 'email',
                                'label' => 'Correo (correo@ejemplo.com)',
                                'placeholder' => 'Correo (correo@ejemplo.com)',
                                'icon' => 'fas fa-at',
                            ])
                            @include('partials.input', [
                                'name' => 'telefono',
                                'label' => 'Telefono (10 Digitos)',
                                'placeholder' => 'Telefono (10 Digitos)',
                                'icon' => 'fas fa-phone',
                            ])
                            @include('partials.select2', [
                                'name' => 'sexo',
                                'label' => 'Sexo...',
                                'placeholder' => 'Sexo...',
                                'options' => [
                                    'Indefinido' => 'Indefinido',
                                    'Hombre' => 'Hombre',
                                    'Mujer' => 'Mujer',
                                ],
                            ])
                        </div>

                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            @include('partials.input', [
                                'name' => 'dias_entrega',
                                'label' => 'Dias de entrega',
                                'placeholder' => 'Dias de entrega',
                                'icon' => 'fas fa-calendar-check',
                            ])
                            @include('partials.select2', [
                                'name' => 'plataforma',
                                'label' => 'Plataforma...',
                                'placeholder' => 'Plataforma...',
                                'options' => [
                                    'Facebook' => 'Facebook',
                                    'Alibaba' => 'Alibaba',
                                    'Aliexpress' => 'Aliexpress',
                                ],
                            ])
                            @include('partials.input', [
                                'name' => 'rfc',
                                'label' => 'RFC (Con homoclave)',
                                'placeholder' => 'RFC (Con homoclave)',
                                'icon' => 'fas fa-address-card',
                            ])
                            @include('partials.textarea', [
                                'name' => 'domicilio',
                                'label' => 'Ingrese el domicilio...',
                                'placeholder' => 'Ingrese el domicilio...',
                                'rows' => 3,
                                'icon' => 'fas fa-home',
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
