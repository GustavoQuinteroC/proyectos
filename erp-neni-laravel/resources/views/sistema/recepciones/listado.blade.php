@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Listado de recepciones</h1>
        <a href="{{ route('recepciones.create') }}" class="btn btn-primary">Nuevo registro</a>
    </div>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Proveedor</th>
                                <th>Almacen</th>
                                <th>Referencia</th>
                                <th>Fecha de recepcion</th>
                                <th>Forma de pago</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    <style>
        .dataTables_wrapper {
            overflow-x: auto;
        }
    </style>
@stop

@section('js')
    <script>
        $(document).ready(function() {
            $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('recepciones.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'socio',
                        name: 'socio'
                    },
                    {
                        data: 'almacen',
                        name: 'almacen'
                    },
                    {
                        data: 'referencia',
                        name: 'referencia'
                    },
                    {
                        data: 'fecha_recepcion',
                        name: 'fecha_recepcion'
                    },
                    {
                        data: 'forma_pago',
                        name: 'forma_pago',
                    },
                    {
                        data: 'action',
                        name: 'action',
                        orderable: false,
                        searchable: false
                    },
                ],
                // Configuración para el idioma español
                language: {
                    "url": "//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
                },
                // Configuración adicional para hacer la tabla responsiva y centrar el contenido
                responsive: true,
                autoWidth: false,
                columnDefs: [{
                        targets: [1],
                        className: 'text-left'
                    }, // Alinear a la izquierda las columnas 1, 2 y 3
                    {
                        targets: [],
                        className: 'text-right'
                    }, // Alinear a la derecha las columnas 4 y 5
                    {
                        targets: '_all',
                        className: 'text-center'
                    } // Centrar el contenido de las demás columnas
                ]
            });
        });
    </script>
@stop
