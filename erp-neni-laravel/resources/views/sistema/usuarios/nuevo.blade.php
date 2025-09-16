@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de usuario</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese la información del usuario</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="MODIFICADO!">
                    El usuario se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('usuarios.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            @include('partials.input', [
                                'name' => 'name',
                                'placeholder' => 'Nombre Completo',
                                'icon' => 'fas fa-user',
                                'label' => 'Nombre Completo',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'email',
                                'type' => 'email',
                                'placeholder' => 'Correo (correo@ejemplo.com)',
                                'icon' => 'fas fa-at',
                                'label' => 'Correo (correo@ejemplo.com)',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'telefono',
                                'placeholder' => 'Teléfono (10 Digitos)',
                                'icon' => 'fas fa-phone',
                                'label' => 'Teléfono (10 Digitos)',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'ine',
                                'placeholder' => 'INE (IDEMEX...)',
                                'icon' => 'fas fa-address-card',
                                'label' => 'INE (IDEMEX...)',
                                'label-class' => 'text-lightblue',
                            ])
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            @include('partials.input', [
                                'name' => 'domicilio',
                                'placeholder' => 'Domicilio',
                                'icon' => 'fas fa-solid fa-home',
                                'label' => 'Domicilio',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'rfc',
                                'placeholder' => 'RFC (Con homoclave)',
                                'icon' => 'fas fa-address-card',
                                'label' => 'RFC (Con homoclave)',
                                'label-class' => 'text-lightblue',
                            ])
                            @include('partials.input', [
                                'name' => 'password',
                                'type' => 'password',
                                'placeholder' => 'Contraseña',
                                'icon' => 'fas fa-key',
                                'label' => 'Contraseña',
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
