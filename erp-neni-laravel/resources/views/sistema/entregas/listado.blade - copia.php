@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Listado de entregas</h1>
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
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Cobrar</th>
                                <th>Venta</th>
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
                ajax: "{{ route('entregas.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'status',
                        name: 'status',
                    },
                    {
                        data: 'fecha',
                        name: 'fecha',
                    },
                    {
                        data: 'hora',
                        name: 'hora',
                    },
                    {
                        data: 'socio',
                        name: 'socio'
                    },
                    {
                        data: 'cobrar_cliente',
                        name: 'cobrar_cliente',
                    },
                    {
                        data: 'idventa',
                        name: 'idventa'
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
                        targets: [3],
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
