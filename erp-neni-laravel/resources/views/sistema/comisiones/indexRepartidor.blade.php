@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Comisiones repartidores por rango de fechas</h1>
    </div>
@stop

@section('content')
    <section class="content">
        <div class="card">
            <div id="dateRangeSlider"></div>
            <div class="card-body">
                <div style="text-align: center;">
                    <p>Rango seleccionado: <span id="selectedRange"></span></p>
                </div>

                <h4 id="totalCostoEntrega" class="text-center"></h4> <!-- Muestra la suma -->
                <div class="table-responsive">
                    <table id="table1" class="table table-striped">
                        <thead>
                            <tr>
                                <th>ID</th>
                                <th>Estado</th>
                                <th>Fecha</th>
                                <th>Hora</th>
                                <th>Cliente</th>
                                <th>Comision</th>
                            </tr>
                        </thead>
                    </table>
                </div>
            </div>
        </div>
    </section>
@stop

@section('css')
    <link href="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.0/nouislider.min.css" rel="stylesheet">
    <style>
        .bg-semiverde {
            background-color: rgba(1, 97, 161, 0.164);
            /* verde semitransparente */
        }
    </style>

@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/noUiSlider/15.6.0/nouislider.min.js"></script>
    <script>
        $(document).ready(function() {
            var dateRangeSlider = document.getElementById('dateRangeSlider');
            var selectedRange = document.getElementById('selectedRange');
            var fechaMinima = new Date();
            fechaMinima.setDate(fechaMinima.getDate() - 60); // Restar 60 días
            fechaMinima.setHours(0, 0, 0, 0); // Establecer a la medianoche para incluir el día completo
            var fechaMaxima = new Date();

            noUiSlider.create(dateRangeSlider, {
                start: [fechaMinima.getTime(), fechaMaxima.getTime()],
                connect: true,
                range: {
                    'min': fechaMinima.getTime(),
                    'max': fechaMaxima.getTime()
                }
            });

            var timeoutId;

            dateRangeSlider.noUiSlider.on('update', function(values) {
                var fechaInicio = new Date(parseInt(values[0]));
                var fechaFin = new Date(parseInt(values[1]));
                selectedRange.textContent = fechaInicio.toLocaleDateString() + ' - ' + fechaFin
                    .toLocaleDateString();

                // Cancelar la solicitud anterior si existe
                clearTimeout(timeoutId);

                // Establecer un tiempo de espera antes de enviar la solicitud AJAX
                timeoutId = setTimeout(function() {
                    // Envía las fechas seleccionadas al servidor
                    $.ajax({
                        url: "{{ route('comisionesRepartidor.index') }}",
                        method: 'GET',
                        data: {
                            fechaInicio: fechaInicio.getTime(),
                            fechaFin: fechaFin.getTime()
                        },
                        success: function(response) {
                            // Actualiza la tabla con los nuevos datos
                            $('#table1').DataTable().clear().rows.add(response.data)
                                .draw();
                            // Actualiza el total del costo de entrega
                            $('#totalCostoEntrega').text('TOTAL DE COMISION $' +
                                response.totalCostoEntrega).addClass('text-center');
                        },
                        error: function(xhr, status, error) {
                            console.error(error);
                        }
                    });
                }, 500); // 500 milisegundos de espera (ajusta este valor según sea necesario)
            });


            $('#table1').DataTable({
                processing: true,
                serverSide: true,
                ajax: {
                    url: "{{ route('comisionesRepartidor.index') }}",
                    data: function(d) {
                        var fechaInicio = new Date(parseInt(dateRangeSlider.noUiSlider.get()[0]));
                        var fechaFin = new Date(parseInt(dateRangeSlider.noUiSlider.get()[1]));
                        d.fechaInicio = fechaInicio.getTime();
                        d.fechaFin = fechaFin.getTime();
                    }
                },
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
                        data: 'costo_entrega',
                        name: 'costo_entrega',
                    },
                ],
                // Función para agregar clase condicional a las filas
                createdRow: function(row, data, dataIndex) {
                    if (data.status === 'Entregado') {
                        $(row).addClass('bg-semiverde');
                    }
                },
                // Configuración para el idioma español
                language: {
                    "url": "//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json"
                },
                // Configuración adicional para hacer la tabla responsiva y centrar el contenido
                responsive: true,
                autoWidth: false,
                searching: false,
                ordering: false,
                paging: false,
                info: false,
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
