@extends('adminlte::page')

@section('title', 'Editar Almacén')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $almacene->name }}</h1>
@stop

@section('content')
    <section class="content">
        <p>Editar la información del almacén</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="MODIFICADO!">
                    El almacen ha sido modificado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('almacenes.update', $almacene) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            <x-adminlte-input name="name" placeholder="Nombre del almacén" label-class="text-lightblue"
                                value="{{ $almacene->name }}">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-warehouse"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <x-adminlte-input name="liusuarios" id="liusuarios" placeholder="Buscar usuario por nombre"
                                value="{{ $usuario->name }}">
                                <x-slot name="appendSlot">
                                    <x-adminlte-button label="Buscar" type="submit" />
                                </x-slot>
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-search"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>
                            <div id="contenedor-sugerencias"></div>
                            <input type="hidden" name="idusuario" id="idusuario"
                                value="{{ $almacene->idusuario_encargado }}">

                            <x-adminlte-input name="capacidad" placeholder="Capacidad máxima (en metros^3)"
                                value="{{ $almacene->capacidad_m3 }}" label-class="text-lightblue" type="number">
                                <x-slot name="prependSlot">
                                    <div class="input-group-text">
                                        <i class="fas fa-dolly"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-input>

                        </div>
                        <div class="col-md-6">
                            <!-- Columna derecha -->

                            <x-adminlte-textarea name="direccion" rows=6 label-class="text-lightblue"
                                placeholder="Dirección del almacén">{{ $almacene->direccion }}
                                <x-slot name="prependSlot">
                                    <div class="input-group-text bg-dark">
                                        <i class="fas fa-map"></i>
                                    </div>
                                </x-slot>
                            </x-adminlte-textarea>
                        </div>
                    </div>
                    <div class="card-header">
                        <div class="row">
                            <div class="col-md-6">
                                <h4>Productos</h4>
                            </div>
                            <div class="col-md-6 text-right">
                                <x-adminlte-button label="Agregar productos" data-toggle="modal"
                                    data-target="#modalProductos" class="btn btn-primary" />
                            </div>
                        </div>
                    </div>

                    <!-- Modal para mostrar la lista de productos -->
                    <x-adminlte-modal id="modalProductos" title="Lista de Productos" size="lg" theme="purple"
                        icon="fas fa-shopping-cart">
                        <div class="modal-body">
                            <table id="tablaProductos" class="table">
                                <thead>
                                    <tr>
                                        <th>ID</th>
                                        <th>Nombre</th>
                                        <th>Descripcion</th>
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
                    <input type="hidden" name="productos_seleccionados" id="productos_seleccionados" value="">
                    {{-- Tabla para mostrar los productos de este almacén --}}
                    <x-adminlte-datatable id="table1" :heads="['ID Producto', 'Catálogo', 'Descripción', 'Acciones']" :config="[
                        'paging' => false,
                        'searching' => false,
                        'info' => false,
                        'ordering' => false,
                        'language' => ['url' => '//cdn.datatables.net/plug-ins/2.0.2/i18n/es-ES.json'],
                        'columnDefs' => [
                            ['width' => '10%', 'targets' => 1],
                            ['width' => '20%', 'targets' => 2],
                            ['width' => '60%', 'targets' => 4],
                            ['width' => '10%', 'targets' => 4],
                        ],
                    ]">
                    </x-adminlte-datatable>


                    <!-- Contenedor del botones -->
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            @include('partials.button', [
                                'type' => 'submit',
                                'label' => 'Guardar Cambios',
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
        // Inicializar la variable productos con el arreglo de productos desde PHP
        var productos = @json($productos);
        var idsProductos = [];


        $(document).ready(function() {
            actualizarTabla(productos);

            $('#liusuarios').autocomplete({
                source: function(request, response) {
                    $.ajax({
                        url: "{{ route('usuarios.buscar') }}",
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
                    $('#idusuario').val(ui.item.id);
                    $('#liusuarios').val(ui.item.label);
                    return false;
                },
                open: function(event, ui) {
                    $('.ui-autocomplete').addClass('autocomplete-suggestions');
                }
            });


            $('#modalProductos').on('show.bs.modal', function(e) {
                // Llamar a la ruta para obtener la lista de productos y mostrar la modal
                $.get('{{ route('productos.getAllProducts') }}', function(data) {
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
            });

            // Esta función se puede usar para obtener los productos seleccionados al enviar el formulario

            // Función para agregar productos seleccionados
            function agregarProductosSeleccionados() {
                var productosSeleccionados = obtenerProductosSeleccionados();

                // Obtener información completa de los productos seleccionados y agregarlos al arreglo
                $.ajax({
                    url: "{{ route('productos.getInfo') }}",
                    type: "POST",
                    data: {
                        productos: productosSeleccionados,
                        _token: '{{ csrf_token() }}'
                    },
                    success: function(response) {
                        // Agregar los productos al arreglo
                        response.forEach(function(producto) {
                            productos.push(producto);
                            idsProductos.push(producto.id);
                            // Establecer los IDs seleccionados en el campo oculto
                            $('#productos_seleccionados').val(JSON.stringify(idsProductos));
                        });
                        // Actualizar la tabla con los datos de los productos obtenidos
                        actualizarTabla(productos);
                        // Cerrar la modal
                        $('#modalProductos').modal('hide');
                    },
                    error: function(xhr, status, error) {
                        // Manejar el error
                    }
                });
            }


            // Función para actualizar la tabla con los productos
            function actualizarTabla(productos) {
                var tablaProductos = $('#table1 tbody');
                tablaProductos.empty(); // Limpiar el cuerpo de la tabla antes de agregar los nuevos productos
                productos.forEach(function(producto) {
                    var fila = $('<tr>');
                    fila.append('<td>' + producto.id + '</td>');
                    fila.append('<td>' + producto.catalogo + '</td>');
                    fila.append('<td>' + producto.descripcion + '</td>');

                    // Verificar si el producto es nuevo
                    var esNuevo = !producto
                        .almacen_producto_id; // Si no hay almacen_producto_id, el producto es nuevo
                    // Agregar el botón de eliminar solo para productos nuevos
                    if (esNuevo) {
                        var iconoEliminar = $('<i>').addClass('fas fa-trash-alt text-danger').attr('title',
                            'Eliminar').css('cursor', 'pointer').click(function() {
                            eliminarProducto(producto.id);
                        });
                        var columnaIcono = $('<td>').addClass('text-center').append(iconoEliminar);
                        fila.append(columnaIcono);
                    } else {
                        fila.append('<td></td>'); // Agregar una celda vacía si el producto no es nuevo
                    }

                    tablaProductos.append(fila);
                });
            }



            /// Función para eliminar un producto del array y actualizar la tabla
            function eliminarProducto(idProducto) {
                // Encontrar el índice del producto en el array
                var indice = -1;
                for (var i = 0; i < productos.length; i++) {
                    if (productos[i].id === idProducto) { // Corregir la comparación aquí
                        indice = i;
                        break;
                    }
                }
                // Si se encontró el índice, eliminar el producto del array
                if (indice !== -1) {
                    productos.splice(indice, 1);
                    // Actualizar la tabla con los productos restantes
                    actualizarTabla(productos);

                    // Actualizar productos_seleccionados sin el ID del producto eliminado
                    idsProductos = productos.map(function(producto) {
                        return producto.id;
                    });
                    $('#productos_seleccionados').val(JSON.stringify(idsProductos));
                }
            }

            function obtenerProductosSeleccionados() {
                var productosSeleccionados = [];
                $('.producto-checkbox:checked').each(function() {
                    productosSeleccionados.push($(this).val());
                });
                return productosSeleccionados;
            }

            $('#modalProductos').on('hidden.bs.modal', function(e) {
                // Limpiar la selección de productos al cerrar la modal
                $('.producto-checkbox').prop('checked', false);
            });

            // Evento clic del botón Aceptar en la modal
            $('#modalProductos button.btn-success').on('click', function() {
                agregarProductosSeleccionados();
            });

            // Eliminar backdrop y clases modal-open al cerrar modal
            $('#modalProductos').on('hidden.bs.modal', function () {
                $('body').removeClass('modal-open');
                $('.modal-backdrop').remove();
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
