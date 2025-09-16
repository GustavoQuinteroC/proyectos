@extends('adminlte::page')

@section('title', 'Consulta de recepción')

@section('content_header')
    <h1 class="m-0 text-dark">Consulta de recepción {{ $recepcione->id }}</h1>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <x-adminlte-input name="selectedSocio" value="{{ $socio->name }}" placeholder="Proveedor" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-user"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="idalmacen" value="{{ $almacen->name }}" placeholder="Almacen" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-warehouse fa-sm"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="forma_pago" value="{{ $recepcione->forma_pago }}"
                            placeholder="Forma de pago" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-wallet"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="cuenta_pago" value="{{ $recepcione->cuenta_pago }}"
                            placeholder="Cuenta de pago" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-credit-card"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                    <div class="col-md-6">
                        <x-adminlte-input name="fecha" value="{{ $recepcione->fecha_recepcion }}"
                            placeholder="Fecha de la recepción" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-calendar-alt"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="guia" value="{{ $recepcione->guia }}" placeholder="Guía de la paquetería"
                            disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-shipping-fast fa-sm"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="referencia" value="{{ $recepcione->referencia }}" placeholder="Referencia"
                            disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-hashtag"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                        <x-adminlte-input name="costos_extras" value="{{ $recepcione->costos_extras }}"
                            placeholder="Costos de manipulación" disabled>
                            <x-slot name="prependSlot">
                                <div class="input-group-text">
                                    <i class="fas fa-dollar-sign fa-lg"></i>
                                </div>
                            </x-slot>
                        </x-adminlte-input>
                    </div>
                </div>
                <div class="card-header">
                    <h4>Productos</h4>
                </div>
                <x-adminlte-datatable id="table1" :heads="['ID Producto', 'Catálogo', 'Descripción', 'Costo', 'Cantidad', 'Importe']" :config="[
                    'paging' => false,
                    'searching' => false,
                    'info' => false,
                    'ordering' => false,
                    'language' => ['url' => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json'],
                    'columnDefs' => [
                        ['width' => '10%', 'targets' => 0],
                        ['width' => '20%', 'targets' => 1],
                        ['width' => '30%', 'targets' => 2],
                        ['width' => '10%', 'targets' => 3],
                        ['width' => '10%', 'targets' => 4],
                        ['width' => '20%', 'targets' => 5],
                    ],
                ]">
                    @foreach ($productos as $producto)
                        <tr>
                            <td>{{ $producto->id }}</td>
                            <td>{{ $producto->catalogo }}</td>
                            <td>{{ $producto->descripcion }}</td>
                            <td>{{ $producto->costo }}</td>
                            <td>{{ $producto->cantidad }}</td>
                            <td>{{ $producto->costo * $producto->cantidad }}</td>
                        </tr>
                    @endforeach
                    <tr>
                        <td colspan="5"><strong>Total</strong></td>
                        <td><strong>{{ $recepcione->total }}</strong></td>
                    </tr>
                </x-adminlte-datatable>

            </div>
        </div>

        <div class="card mt-3">

        </div>
    </section>
@stop
