@extends('adminlte::page')

@section('title', 'edit')

@section('content_header')
    <h1 class="m-0 text-dark">{{ $usuario->name }}</h1>
@stop

@section('content')
    <section class="content">
        <p>Ingrese las modificaciones del usuario</p>
        <div class="card">
            @if (session()->has('message') && session('message') == 'ok')
                <x-adminlte-alert theme="success" title="MODIFICADO!">
                    El usuario ha sido modificado de manera exitosa!
                </x-adminlte-alert>
            @endif
            <div class="card-body">
                <form action="{{ route('usuarios.update', $usuario) }}" method="post">
                    @csrf
                    @method('PUT')
                    <div class="row">
                        <div class="col-md-6">
                            <!-- Columna izquierda -->
                            @include('partials.input', [
                                'name' => 'name',
                                'label' => 'Nombre',
                                'value' => $usuario->name,
                                'icon' => 'fas fa-user',
                            ])
                            @include('partials.input', [
                                'name' => 'email',
                                'type' => 'email',
                                'label' => 'Correo',
                                'value' => $usuario->email,
                                'icon' => 'fas fa-at',
                            ])
                            @include('partials.input', [
                                'name' => 'telefono',
                                'label' => 'Teléfono',
                                'value' => $usuario->telefono,
                                'icon' => 'fas fa-phone',
                            ])
                            @include('partials.input', [
                                'name' => 'ine',
                                'label' => 'INE',
                                'value' => $usuario->ine,
                                'icon' => 'fas fa-address-card',
                            ])
                        </div>

                        <div class="col-md-6">
                            <!-- Columna derecha -->
                            @include('partials.input', [
                                'name' => 'domicilio',
                                'label' => 'Domicilio',
                                'value' => $usuario->domicilio,
                                'icon' => 'fas fa-home',
                            ])
                            @include('partials.input', [
                                'name' => 'rfc',
                                'label' => 'RFC',
                                'value' => $usuario->rfc,
                                'icon' => 'fas fa-address-card',
                            ])
                            @include('partials.input', [
                                'name' => 'password',
                                'type' => 'password',
                                'label' => 'Contraseña',
                                'icon' => 'fas fa-key',
                            ])
                        </div>
                    </div>
                    <!-- Contenedor del botonera -->
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex justify-content-end">
                            <x-adminlte-button type="submit" class="btn-primary" icon="fas fa-save" label="Actualizar"/>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>
@stop


@section('js')
    <script>
        $(document).ready(function() {
            $('#deleteButton').click(function() {
                const deleteUrl = "{{ route('usuarios.destroy', $usuario->id) }}";
                const csrfToken = $('meta[name="csrf-token"]').attr('content');

                Swal.fire({
                    title: '¿Estás seguro de que quieres eliminar al usuario {{ $usuario->name }}?',
                    text: 'Esta acción es irreversible.',
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonText: 'Sí, eliminar!',
                    cancelButtonText: 'No, cancelar',
                    reverseButtons: true
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: deleteUrl,
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': csrfToken
                            },
                            success: function(response) {
                                if (response.success) {
                                    Swal.fire({
                                        title: '¡Eliminado!',
                                        text: 'El usuario ha sido eliminado exitosamente.',
                                        icon: 'success'
                                    }).then(() => {
                                        window.location.href = "{{ route('usuarios.index') }}"; // Redirigir a la página de usuarios
                                    });
                                } else {
                                    // Si el servidor devuelve un mensaje de error
                                    Swal.fire({
                                        title: 'Error',
                                        text: response.message || 'Ocurrió un error al eliminar el usuario. Intenta nuevamente.',
                                        icon: 'error'
                                    });
                                }
                            },
                            error: function(xhr, status, error) {
                                // Si hay un error con la solicitud
                                console.error('Error eliminando el usuario:', error);
                                Swal.fire({
                                    title: 'Error',
                                    text: 'Ocurrió un error al eliminar el usuario. Intenta nuevamente.',
                                    icon: 'error'
                                });
                            }
                        });
                    } else if (result.dismiss === Swal.DismissReason.cancel) {
                        Swal.fire({
                            title: 'Cancelado',
                            text: 'El usuario no ha sido eliminado.',
                            icon: 'error'
                        });
                    }
                });
            });
        });
    </script>
@stop