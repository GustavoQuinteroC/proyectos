@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $socio->name }}</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese la informacion de la venta</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="Guardado">
                    La venta se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('ventas.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <h4>Datos de la venta:</h4>
                            <!-- Columna izquierda -->
                            <x-adminlte-input name="nombre_cliente" id="nombre_cliente" value="{{ $socio->name }}"
                                readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input type="tel" name="telefono" value="{{ $socio->telefono }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-phone"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="almacen" value="{{ $almacen->name }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="forma_pago" value="{{ $venta->forma_pago }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-wallet"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="cuenta_pago" value="{{ $venta->cuenta_pago }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            <h4>Datos de la entrega:</h4>
                            <x-adminlte-input name="nombre_punto" id="nombre_punto" value="{{ $punto_entrega->name }}"
                                readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="nombre_usuario" id="nombre_usuario" value="{{ $usuario->name }}"
                                readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="fecha" type="date" value="{{ $entrega->fecha }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="hora" type="time" value="{{ $entrega->hora }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="notas_entrega" id="notas_entrega" type="text"
                                value="{{ $venta->notas }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                            <!-- Campo para el Costo por Entrega -->
                            <x-adminlte-input name="costo_entrega" id="costo_entrega" type="number" step="0.01"
                                value="{{ $entrega->costo_entrega }}" readonly>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                    <div class="col-md-12">
                        <h5 class="text-center">Productos</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>catalogo</th>
                                    <th>Precio</th>
                                    <th>Cantidad</th>
                                    <th>Importe</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->id }}</td>
                                        <td>{{ $producto->catalogo }}</td>
                                        <td>{{ $producto->preciou }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                        <td>{{ $producto->preciou * $producto->cantidad }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <div class="row">
                        <div class="col-md-10"></div>
                        <div class="col-md-2">
                            <tfoot>
                                <tr>
                                    <td colspan="5"></td>
                                    <td><strong>Total</strong></td>
                                    <td id="total-importe"><strong>{{ $venta->total }}</strong></td>
                                    <!-- ID para actualizar el total de importe -->
                                </tr>
                            </tfoot>
                        </div>
                    </div>
                    <!-- Contenedor del botonera -->
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <!--<x-adminlte-button type="submit" label="Guardar" theme="primary" icon="fas fa-save" />-->
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop
