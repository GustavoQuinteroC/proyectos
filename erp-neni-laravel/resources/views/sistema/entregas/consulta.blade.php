@extends('adminlte::page')

@section('title', 'Editar entrega')

@section('content_header')
    <h1 class="m-0 text-dark">Editar Entrega</h1>
@stop

@section('content')
    <section class="content">
        <p>Editar la información de la entrega</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="MODIFICADO!">
                    La entrega ha sido modificada de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('entregas.update', $entrega) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            <h4 class="text-center">Datos de la venta</h4>
                            <x-adminlte-input name="socio" placeholder="Socio" readonly value="{{ $socio->name }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-users"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Socio
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="vendedor" placeholder="Vendedor" readonly value="{{ $usuario->name }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-user"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Vendedor
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="fecha_venta" type="date" placeholder="Fecha de venta" readonly value="{{ $venta->created_at->format('Y-m-d') }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Fecha de venta
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="notas_venta" placeholder="Notas de la venta" readonly value="{{ $venta->notas }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Notas de la venta
                                </x-slot>
                            </x-adminlte-input>
                            <h4 class="text-center">Costos de la venta</h4>
                            <x-adminlte-input name="cobrar_cliente" placeholder="Cobrar al cliente" readonly value="{{ $entrega->cobrar_cliente }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-money-bill-wave"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Cobrar al cliente
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="comision_entrega" placeholder="Comisión por la entrega" readonly value="{{ $entrega->costo_entrega }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-hand-holding-usd"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Comisión por la entrega
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            <h4 class="text-center">Lugar de entrega</h4>
                            <x-adminlte-input name="lugar_clasificacion" placeholder="Clasificación" readonly value="{{ $punto_entrega->clasificacion }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-train"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Clasificación
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="lugar_linea" placeholder="Linea" readonly value="{{ $punto_entrega->linea }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-train"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Linea
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="lugar_estacion" placeholder="Estación" readonly value="{{ $punto_entrega->name }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-map-marker"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Estación
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="lugar_fecha" type="date" placeholder="Fecha" readonly value="{{ $entrega->fecha }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-alt"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Fecha
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="lugar_hora" type="time" placeholder="Hora" readonly value="{{ $entrega->hora }}" label-class="text-lightblank">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-clock"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Hora
                                </x-slot>
                            </x-adminlte-input>
                            <h4 class="text-center">Datos del mensajero</h4>
                            <x-adminlte-select name="estado" label-class="text-lightblank" readonly>
                                <option value="Por contactar" {{ $entrega->status == 'Por contactar' ? 'selected' : '' }} {{ $entrega->status == 'Contactado' || $entrega->status == 'Confirmado' || $entrega->status == 'Entregado' || strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Por contactar</option>
                                <option value="Contactado" {{ $entrega->status == 'Contactado' ? 'selected' : '' }} {{ $entrega->status == 'Confirmado' || $entrega->status == 'Entregado' || strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Contactado</option>
                                <option value="Confirmado" {{ $entrega->status == 'Confirmado' ? 'selected' : '' }} {{ $entrega->status == 'Entregado' || strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Confirmado</option>
                                <option value="Entregado" {{ $entrega->status == 'Entregado' ? 'selected' : '' }} {{ strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Entregado</option>
                                <option value="Cancelado - Sin respuesta" {{ $entrega->status == 'Cancelado - Sin respuesta' ? 'selected' : '' }} {{ strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Cancelado - Sin respuesta</option>
                                <option value="Cancelado - Con respuesta" {{ $entrega->status == 'Cancelado - Con respuesta' ? 'selected' : '' }} {{ strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Cancelado - Con respuesta</option>
                                <option value="Cancelado - No recibio" {{ $entrega->status == 'Cancelado - No recibio' ? 'selected' : '' }} {{ strpos($entrega->status, 'Cancelado') !== false ? 'disabled' : '' }}>Cancelado - No recibio</option>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-truck"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Estado de la entrega
                                </x-slot>
                            </x-adminlte-select>
                            <x-adminlte-input name="notas_mensajero" placeholder="Notas del mensajero" value="{{ $entrega->notas }}" id="notas_mensajero">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-sticky-note"></i>
                                    </div>
                                </x-slot>
                                <x-slot name="label">
                                    Notas del mensajero
                                </x-slot>
                            </x-adminlte-input>
                            
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
                    </div>
                    <div class="row mt-3" id="updateButton" {{ $entrega->status == 'Entregado' || strpos($entrega->status, 'Cancelado') !== false ? 'style=display:none;' : '' }}>
                        <div class="col-md-12 d-flex justify-content-end">
                            <x-adminlte-button type="submit" class="btn-primary" icon="fas fa-save" label="Actualizar" />
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop


@section('js')
<script>
    document.addEventListener("DOMContentLoaded", function() {
        var entregaStatus = "{{ $entrega->status }}";
        var notasInput = document.getElementById("notas_mensajero");

        function handleChange() {
            if (entregaStatus === 'Entregado' || entregaStatus.includes('Cancelado')) {
                notasInput.setAttribute('readonly', 'readonly');
            } else {
                notasInput.removeAttribute('readonly');
            }
        }

        handleChange(); // Llama a la función una vez para establecer el estado inicial

        // Agrega un evento change al select para actualizar el estado del input
        document.getElementById("estado").addEventListener("change", function() {
            entregaStatus = this.value;
            handleChange();
        });
    });
</script>

@stop