@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">Registro de recepcion</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese la informacion de la recepcion</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="Guardado">
                    La recepcion se ha guardado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('recepciones.store') }}" method="post">
                    @csrf
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            <x-adminlte-input name="nombre_proveedor" id="nombre_proveedor"
                                placeholder="Buscar proveedor por nombre">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text text-blank">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <div id="contenedor-sugerencias"></div>
                            <input type="hidden" name="selectedSocio" id="selectedSocio" value="">
                            <input type="hidden" name="productos_seleccionados" id="productos_seleccionados"
                                value="">
                            <input type="hidden" name="total_hidden" id="total_hidden" value="">
                            <x-adminlte-select2 name="idalmacen" label-class="text-lightblue" data-placeholder="Almacen...">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-warehouse fa-sm"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="['' => 'Almacen...'] + $almacenes->pluck('name', 'id')->toArray()" selected="" />
                            </x-adminlte-select2>
                            <x-adminlte-select2 name="forma_pago" label-class="text-blank"
                                data-placeholder="Forma de pago...">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-solid fa-wallet"></i>
                                    </div>
                                </x-slot>
                                <x-adminlte-options :options="[
                                    '' => 'Forma de pago...',
                                    'SPEI' => 'SPEI',
                                    'Efectivo' => 'Efectivo',
                                    'Tranferencia' => 'Tranferencia',
                                    'Deposito bancario' => 'Deposito bancario',
                                    'Pago en plataforma' => 'Pago en plataforma',
                                ]" />
                            </x-adminlte-select2>
                            <x-adminlte-input name="cuenta_pago" placeholder="Cuenta de pago">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-credit-card"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            <x-adminlte-input name="fecha" placeholder="Fecha de la recepcion" type="date"
                                value="{{ now()->format('Y-m-d') }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-calendar-check"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="guia" placeholder="Guia de la paqueteria">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-shipping-fast fa-sm"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="referencia" placeholder="Referencia">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-hashtag"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="costos_extras" placeholder="Costos de manipulacion" type="number"
                                step="any">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-dollar-sign"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Productos</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <button id="btnAgregarProductos" type="button" class="btn btn-primary">Agregar
                                    productos</button>
                            </div>
                        </div>
                    </div>
                    <!-- Modal para mostrar la lista de productos -->
                    <x-adminlte-modal id="modalProductos" title="Lista de productos" size="lg" theme="purple"
                        icon="fas fa-shopping-cart">
                        <div class="modal-body">
                            <table id="tablaProductos" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripción</th>
                                        <th style="text-align: center;">Agregar</th> {{-- Estilo centrado --}}
                                    </tr>
                                </thead>
                                <tbody>

                                </tbody>
                            </table>
                        </div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" theme="danger" label="Cancelar" data-dismiss="modal" />
                            <x-adminlte-button theme="success" label="Aceptar" />
                        </x-slot>
                    </x-adminlte-modal>



                    <x-adminlte-modal id="modalEditarProducto" title="Editar producto" size="md" theme="purple"
                        icon="fas fa-edit">
                        <div class="modal-body">
                            <input type="hidden" id="edit-product-id">
                            <p><strong>Catálogo:</strong> <span id="edit-product-catalog"></span></p>
                            <p><strong>Descripción:</strong> <span id="edit-product-description"></span></p>
                            <x-adminlte-input name="edit-product-cost" id="edit-product-cost" label="Costo"
                                placeholder="Ingrese el costo del producto" type="number" step="any" />
                            <x-adminlte-input name="edit-product-quantity" id="edit-product-quantity" label="Cantidad"
                                placeholder="Ingrese la cantidad del producto" type="number" />
                        </div>
                        <x-slot name="footerSlot">
                            <x-adminlte-button class="mr-auto" theme="danger" label="Cancelar" data-dismiss="modal" />
                            <x-adminlte-button class="editar-producto" theme="success" label="Guardar" />
                        </x-slot>
                    </x-adminlte-modal>
                    <!-- Tabla para mostrar los productos de este almacén -->
                    <x-adminlte-datatable id="table1" :heads="['ID', 'Catálogo', 'Descripción', 'Costo', 'Cantidad', 'Importe', 'Acciones']" :config="[
                        'paging' => false,
                        'searching' => false,
                        'info' => false,
                        'ordering' => false,
                        'language' => ['url' => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json'],
                        'columnDefs' => [
                            ['width' => '5%', 'targets' => 0],
                            ['width' => '20%', 'targets' => 1],
                            ['width' => '30%', 'targets' => 2],
                            ['width' => '15%', 'targets' => 3],
                            ['width' => '10%', 'targets' => 4],
                            ['width' => '15%', 'targets' => 5],
                        ],
                    ]">
                        <tfoot>
                            <tr>
                                <td colspan="5"></td>
                                <td><strong>Total</strong></td>
                                <td id="total-importe"><strong>0.00</strong></td>
                                <!-- ID para actualizar el total de importe -->
                            </tr>
                        </tfoot>
                    </x-adminlte-datatable>

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


@section('js')
    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.4.min.js"></script>
    <!-- jQuery UI -->
    <script src="https://code.jquery.com/ui/1.12.1/jquery-ui.min.js"></script>
    <!-- Bootstrap JavaScript -->
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"></script>
    <script>
        $(document).ready(function() {
            var productos = @json($productos);
            var idsProductos = [];


            // Autocompletado para clientes
            $('#nombre_proveedor').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('proveedores.buscar') }}",
                        type: "GET",
                        dataType: "json",
                        data: {
                            search: request.term
                        },
                        success: function(data) {
                            response(data);
                        }
                    });
                },
                select: function(event, ui) {
                    $('#selectedSocio').val(ui.item.id);
                    $('#nombre_proveedor').val(ui.item.label);
                    return false;
                },
                open: function(event, ui) {
                    $('.ui-autocomplete').addClass('autocomplete-suggestions');
                }
            });


            $('#btnAgregarProductos').on('click', function() {
                var idalmacen = $('#idalmacen').val();
                if (!idalmacen) {
                    Swal.fire({
                        title: "¡Error!",
                        text: "Por favor, seleccione un almacén antes de agregar productos.",
                        icon: "error"
                    });
                } else {
                    $('#modalProductos').modal('show');
                    cargarProductosEnModal();
                }
            });




            $('#modalProductos button.btn-success').on('click', function() {
                agregarProductosSeleccionados();
            });

            $('#modalEditarProducto .editar-producto').on('click', function() {
                guardarCambiosProducto();
            });

            $('#costos_extras').on('input', function() {
                actualizarTabla(productos);
            });


            function cargarProductosEnModal() {
                var idalmacen = $('#idalmacen')
                    .val(); // Obtener el idalmacen del formulario o de donde lo tengas almacenado

                $.get('{{ route('productos.getProductsForAlmacen') }}', {
                    idalmacen: idalmacen
                }, function(data) {
                    $('#tablaProductos tbody').empty();
                    data.forEach(function(producto) {
                        $('#tablaProductos tbody').append('<tr><td>' + producto.id +
                            '</td><td>' + producto.catalogo + '</td><td>' + producto
                            .descripcion +
                            '</td><td style="text-align: center;"><input type="checkbox" class="producto-checkbox" value="' +
                            producto.id +
                            '" style="margin-left:auto;margin-right:auto;display:block;" /></td></tr>'
                        );
                    });
                });
            }


            function agregarProductosSeleccionados() {
                var productosSeleccionados = obtenerProductosSeleccionados();

                $.ajax({
                    url: "{{ route('productos.getInfo') }}",
                    type: "POST",
                    data: {
                        productos: productosSeleccionados,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        response.forEach(function(producto) {
                            producto.cantidad = 1;
                            productos.push(producto);
                            idsProductos.push(producto.id);
                        });
                        actualizarTabla(productos);
                        $('#modalProductos').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        // Manejar el error
                    }
                });
            }

            function actualizarTabla(productos) {
                var tablaProductos = $('#table1 tbody');
                var totalImporte = 0;
                var costoManipulacion = parseFloat($('#costos_extras').val()) ||
                    0; // Obtener el costo de manipulación
                tablaProductos.empty();
                var productosSeleccionados = [];

                productos.forEach(function(producto) {
                    var importe = parseFloat(producto.costo) * parseInt(producto.cantidad);
                    totalImporte += importe;

                    var fila = $('<tr>');
                    fila.append('<td>' + producto.id + '</td>');
                    fila.append('<td>' + producto.catalogo + '</td>');
                    fila.append('<td>' + producto.descripcion + '</td>');
                    fila.append('<td>' + producto.costo + '</td>');
                    fila.append('<td>' + producto.cantidad + '</td>');
                    fila.append('<td>' + importe.toFixed(2) +
                        '</td>'); // Redondear el importe a dos decimales

                    // Agregar los iconos de eliminar y editar en la misma columna
                    var columnaIconos = $('<td>').addClass('text-center');
                    var iconoEliminar = $('<i>').addClass('fas fa-trash-alt text-danger mr-2').attr('title',
                        'Eliminar').css('cursor', 'pointer').click(function() {
                        eliminarProducto(producto.id);
                    });
                    var iconoEditar = $('<i>').addClass('fas fa-edit text-primary').attr('title', 'Editar')
                        .css('cursor', 'pointer').click(function() {
                            editarProducto(producto.id);
                        });
                    columnaIconos.append(iconoEliminar, iconoEditar);
                    fila.append(columnaIconos);

                    tablaProductos.append(fila);

                    // Agregar producto seleccionado con nueva información
                    productosSeleccionados.push({
                        id: producto.id,
                        costo: producto.costo,
                        cantidad: producto.cantidad
                    });
                });

                // Actualizar productos_seleccionados con la nueva información de los productos
                $('#productos_seleccionados').val(JSON.stringify(productosSeleccionados));

                // Agregar el costo de manipulación al total de importe
                totalImporte += costoManipulacion;

                // Actualizar total de importe
                $('#total-importe').text(totalImporte.toFixed(2));
                $('#total_hidden').val(totalImporte.toFixed(2));
            }

            function guardarCambiosProducto() {
                var idProducto = $('#edit-product-id').val();
                var costo = $('#edit-product-cost').val();
                var cantidad = $('#edit-product-quantity').val();

                // Buscar el producto por su ID
                var productoIndex = productos.findIndex(function(prod) {
                    return prod.id === parseInt(idProducto);
                });

                // Actualizar el costo y la cantidad del producto
                if (productoIndex !== -1) {
                    productos[productoIndex].costo = parseFloat(costo).toFixed(2);
                    productos[productoIndex].cantidad = parseInt(cantidad);
                }

                // Actualizar la tabla
                actualizarTabla(productos);

                // Cerrar la modal
                $('#modalEditarProducto').modal('hide');
            }

            function editarProducto(idProducto) {
                // Buscar el producto por su ID
                var producto = productos.find(function(prod) {
                    return prod.id === idProducto;
                });

                // Llenar los campos de la modal con los datos del producto
                $('#edit-product-id').val(producto.id);
                $('#edit-product-catalog').text(producto.catalogo);
                $('#edit-product-description').text(producto.descripcion);
                $('#edit-product-cost').val(producto.costo);
                $('#edit-product-quantity').val(producto.cantidad);

                // Mostrar la modal de edición
                $('#modalEditarProducto').modal('show');
            }

            function eliminarProducto(idProducto) {
                var indice = productos.findIndex(function(prod) {
                    return prod.id === idProducto;
                });
                if (indice !== -1) {
                    productos.splice(indice, 1);
                    actualizarTabla(productos);
                }
            }

            function obtenerProductosSeleccionados() {
                return $('.producto-checkbox:checked').map(function() {
                    return $(this).val();
                }).get();
            }

            $('#modalProductos').on('hidden.bs.modal', function(e) {
                $('.producto-checkbox').prop('checked', false);
            });
        });
    </script>
@stop



@section('css')
    <style>
        .autocomplete-suggestions {
            width: 39% !important;
            max-height: 200px;
            overflow-y: auto;
            border: 1px solid #ccc;
            background-color: #3a3e42;
            color: #ccc;
            padding: 0;
            /* Resetear el relleno */
            margin: 0;
            /* Resetear el margen */
        }

        .autocomplete-suggestions li {
            padding: 5px 10px;
            /* Añadir relleno deseado */
            text-align: left;
            list-style-type: none;
            /* Eliminar los puntos de la lista */
            margin: 0;
            /* Resetear el margen */
        }

        .autocomplete-suggestions li:hover {
            background-color: #f5f5f5;
            color: #585f66;
            /* Cambiar color de texto al pasar el cursor */
            cursor: pointer;
        }
    </style>
@stop
