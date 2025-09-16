@extends('adminlte::page')

@section('title', 'Inventario por Almacén')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Inventario por Almacén</h1>
    </div>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-header">
                <h3 class="card-title">Almacén:</h3>
                <div class="card-tools">
                    <form action="{{ route('inventarios.index') }}" method="GET">
                        <div class="input-group input-group-sm" style="width: 200px;">
                            <select class="form-control" name="almacen_id" onchange="this.form.submit()">
                                <option value="">Seleccione un almacén</option>
                                @foreach($almacenes as $almacen)
                                    <option value="{{ $almacen->id }}" {{ $almacen_id == $almacen->id ? 'selected' : '' }}>
                                        {{ $almacen->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>
                    </form>
                </div>
            </div>
            <div class="card-body">
                @if($almacen_id)
                    <div class="table-responsive">
                        <table class="table table-striped">
                            <thead>
                                <tr>
                                    <th>ID</th>
                                    <th>Producto</th>
                                    <th>Existencia</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($productos as $producto)
                                    <tr>
                                        <td>{{ $producto->id }}</td>
                                        <td>{{ $producto->catalogo }}</td>
                                        <td>{{ $producto->existencia }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                @endif
            </div>
        </div>
    </section>
@stop
