@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Listado de Puntos de entrega</h1>
        <x-adminlte-button label="Nuevo registro" data-toggle="modal" data-target="#modalNuevoPuntoEntrega"
            class="btn btn-primary" />
    </div>
@stop

@section('content')
    <section class="content">
        <div class="card">
            @if (session()->has('guardado') && session('guardado') == 'ok')
                <x-adminlte-alert theme="success" title="¡GUARDADO!">
                    El punto de entrega se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            @if (session()->has('actualizado') && session('actualizado') == 'ok')
                <x-adminlte-alert theme="success" title="¡ACTUALIZADO!">
                    El punto de entrega se ha actualizado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Nombre</th>
                                <th>Clasificación</th>
                                <th>Línea</th>
                                <th>Latitud</th>
                                <th>Longitud</th>
                                <th>Acciones</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>

    <!-- Modal Nuevo Punto de Entrega -->
    <x-adminlte-modal id="modalNuevoPuntoEntrega" title="Nuevo Punto de Entrega" theme="purple" icon="fas fa-plus"
        size='md' disable-animations no-close>
        <form id="formNuevoPuntoEntrega" action="{{ route('puntos_entregas.store') }}" method="POST">
            @csrf
            <div class="modal-body">
                <div class="form-group">
                    <x-adminlte-input name="nombre" label="Nombre" placeholder="Ingrese el nombre del punto de entrega" />
                </div>
                <div class="form-group">
                    <x-adminlte-select name="clasificacion" label="Clasificación" label-class="text-lightblue"
                        igroup-size="md">
                        <option value="Tren">Tren</option>
                        <option value="Macrobus">Macrobus</option>
                    </x-adminlte-select>
                </div>
                <div class="form-group" id="linea_group">
                    <x-adminlte-select name="linea" label="Línea" label-class="text-lightblue" igroup-size="md">
                    </x-adminlte-select>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="latitud" label="Latitud" placeholder="Ingrese la latitud" />
                </div>
                <div class="form-group">
                    <x-adminlte-input name="longitud" label="Longitud" placeholder="Ingrese la longitud" />
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button label="Cancelar" theme="danger" data-dismiss="modal" />
                <x-adminlte-button label="Guardar" theme="success"
                    onclick="event.preventDefault(); document.getElementById('formNuevoPuntoEntrega').submit();" />
            </x-slot>
        </form>
    </x-adminlte-modal>
    <!-- Modal Nuevo Punto de Entrega -->

    <!-- Modal Editar Punto de Entrega -->
    <x-adminlte-modal id="modalEditarPuntoEntrega" title="Editar Punto de Entrega" theme="purple" icon="fas fa-edit"
        size='md' disable-animations no-close>
        <form id="formEditarPuntoEntrega" action="" method="POST">
            @csrf
            @method('PUT')
            <div class="modal-body">
                <div class="form-group">
                    <x-adminlte-input name="nombre_edit" label="Nombre"
                        placeholder="Ingrese el nombre del punto de entrega" />
                </div>
                <div class="form-group">
                    <x-adminlte-select name="clasificacion_edit" label="Clasificación" label-class="text-lightblue"
                        igroup-size="md">
                        <option value="Tren">Tren</option>
                        <option value="Macrobus">Macrobus</option>
                    </x-adminlte-select>
                </div>
                <div class="form-group" id="linea_group_edit">
                    <x-adminlte-select name="linea_edit" label="Línea" label-class="text-lightblue" igroup-size="md">
                    </x-adminlte-select>
                </div>
                <div class="form-group">
                    <x-adminlte-input name="latitud_edit" label="Latitud" placeholder="Ingrese la latitud" />
                </div>
                <div class="form-group">
                    <x-adminlte-input name="longitud_edit" label="Longitud" placeholder="Ingrese la longitud" />
                </div>
            </div>
            <x-slot name="footerSlot">
                <x-adminlte-button label="Cancelar" theme="danger" data-dismiss="modal" />
                <x-adminlte-button label="Guardar" theme="success"
                    onclick="event.preventDefault(); document.getElementById('formEditarPuntoEntrega').submit();" />
            </x-slot>
        </form>
    </x-adminlte-modal>
    <!-- Modal Editar Punto de Entrega -->
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
            // Evento change para actualizar las opciones de línea
            $('select[name="clasificacion"]').change(function() {
                var clasificacion = $(this).val();
                var selectLinea = $('select[name="linea"]');
                selectLinea.empty();

                if (clasificacion === 'Tren') {
                    selectLinea.append('<option value="1">Línea 1</option>');
                    selectLinea.append('<option value="2">Línea 2</option>');
                    selectLinea.append('<option value="3">Línea 3</option>');
                } else if (clasificacion === 'Macrobus') {
                    selectLinea.append('<option value="Periferico">Periferico</option>');
                    selectLinea.append('<option value="Calzada">Calzada</option>');
                }
            });

            // Ejecutar el evento change al cargar la página
            $('select[name="clasificacion"]').trigger('change');

            $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: "{{ route('puntos_entregas.index') }}",
                columns: [{
                        data: 'id',
                        name: 'id'
                    },
                    {
                        data: 'name',
                        name: 'name'
                    },
                    {
                        data: 'clasificacion',
                        name: 'clasificacion'
                    },
                    {
                        data: 'linea',
                        name: 'linea'
                    },
                    {
                        data: 'latitud',
                        name: 'latitud'
                    },
                    {
                        data: 'longitud',
                        name: 'longitud'
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
            // Open modal on validation error
            @if ($errors->any())
                $('#modalNuevoPuntoEntrega').modal('show');
            @endif
        });

        // Editar Punto de Entrega
        // Editar Punto de Entrega
        function editarPuntoEntrega(id) {
            $.ajax({
                url: "{{ route('puntos_entregas.index') }}" + '/' + id + '/edit',
                type: "GET",
                dataType: "JSON",
                success: function(data) {
                    $('#modalEditarPuntoEntrega').modal('show');
                    $('#formEditarPuntoEntrega').attr('action', '{{ route('puntos_entregas.update', ':id') }}'
                        .replace(':id', id));
                    $('#formEditarPuntoEntrega input[name="nombre_edit"]').val(data.name);
                    $('#formEditarPuntoEntrega select[name="clasificacion_edit"]').val(data.clasificacion);
                    $('#formEditarPuntoEntrega input[name="latitud_edit"]').val(data.latitud);
                    $('#formEditarPuntoEntrega input[name="longitud_edit"]').val(data.longitud);

                    var selectLineaEdit = $('#formEditarPuntoEntrega #linea_group_edit select');
                    selectLineaEdit.empty();

                    if (data.clasificacion === 'Tren') {
                        selectLineaEdit.append('<option value="1">Línea 1</option>');
                        selectLineaEdit.append('<option value="2">Línea 2</option>');
                        selectLineaEdit.append('<option value="3">Línea 3</option>');
                    } else if (data.clasificacion === 'Macrobus') {
                        selectLineaEdit.append('<option value="Periferico">Periferico</option>');
                        selectLineaEdit.append('<option value="Calzada">Calzada</option>');
                    }
                    $('#formEditarPuntoEntrega select[name="linea_edit"]').val(data.linea);
                },
                error: function(data) {
                    console.log('Error:', data);
                }
            });
        }
    </script>
@stop
