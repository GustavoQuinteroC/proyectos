@extends('adminlte::page')

@section('title', 'Detalle de Movimiento')

@section('content_header')
    <h1 class="m-0 text-dark">Detalle de Movimiento</h1>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="row">
                    <div class="col-md-6">
                        <div class="form-group">
                            <label for="almacen">Almacén:</label>
                            <input type="text" id="almacen" class="form-control" value="{{ $almacen->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="socio">Socio:</label>
                            <input type="text" id="socio" class="form-control" value="{{ $socio->name }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="fecha">Fecha:</label>
                            <input type="text" id="fecha" class="form-control" value="{{ $movimiento->created_at }}" readonly>
                        </div>
                        <div class="form-group">
                            <label for="concepto">Concepto:</label>
                            <input type="text" id="concepto" class="form-control" value="{{ $tipo_movimiento->concepto }}" readonly>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <h5 class="text-center">Productos</h5>
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Cantidad</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->id }}</td>
                                        <td>{{ $producto->catalogo }}</td>
                                        <td>{{ $producto->cantidad }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </section>
@stop

