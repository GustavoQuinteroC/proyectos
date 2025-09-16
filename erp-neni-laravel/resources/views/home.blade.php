@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <h1 class="m-0 text-dark">Dashboard</h1>
@stop

@section('content')
    <section class="content">
        <div class="container-fluid">
            <div class="row">
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-purple">
                        <div class="inner">
                            @if($existenciaProductoMayorPorcentaje)
                                <h3>{{ $existenciaProductoMayorPorcentaje->producto }}</h3>
                                <p>Existencia total: {{ $existenciaProductoMayorPorcentaje->total_existencia }}</p>
                            @else
                                <h3>Sin datos</h3>
                                <p>Existencia total: 0</p>
                            @endif
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                        <a href="{{ route('inventarios.index') }}" class="small-box-footer">Ir al modulo de inventario <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-pink">
                        <div class="inner">
                            <h3>${{ $comisionesVendedor }}</h3>
                            <p>Comisiones vendedores (últimos 30 días)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <a href="{{ route('ventas.index') }}" class="small-box-footer">Ir al modulo de ventas <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-6">
                    <div class="small-box bg-teal">
                        <div class="inner">
                            <h3>#{{ $totalVentas }}</h3>
                            <p>Ventas entregadas totales (últimos 30 días)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-shopping-bag"></i>
                        </div>
                        <a href="{{ route('ventas.index') }}" class="small-box-footer">Ir al módulo de ventas <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
                
                <div class="col-lg-3 col-6">
                    <div class="small-box bg-info">
                        <div class="inner">
                            <h3>${{ $comisionesRepartidor }}</h3>
                            <p>Comisiones repartidores (últimos 30 días)</p>
                        </div>
                        <div class="icon">
                            <i class="fas fa-chart-bar"></i>
                        </div>
                        <a href="{{ route('entregas.index') }}" class="small-box-footer">Ir al modulo de entregas <i class="fas fa-arrow-circle-right"></i></a>
                    </div>
                </div>
            </div>
            
            <div class="row">
                <!-- Gráfica de Ventas Totales por Producto -->
                <section class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-purple">
                        <div class="card-header">
                            <h3 class="card-title">Productos Vendidos</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($productosVendidosEnElMes->isEmpty())
                                <p>Sin datos disponibles</p>
                            @else
                                <canvas id="donutChart"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 100%;"></canvas>
                            @endif
                        </div>
                    </div>
                </section>

                <!-- Gráfica de Ventas por Usuario -->
                <section class="col-lg-3 col-md-6 col-sm-12">
                    <div class="card card-pink">
                        <div class="card-header">
                            <h3 class="card-title">Ventas por vendedor</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($ventasPorUsuario->isEmpty())
                                <p>Sin datos disponibles</p>
                            @else
                                <canvas id="donutChart2"
                                    style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 100%;"></canvas>
                            @endif
                        </div>
                    </div>
                </section>
                
                <!-- Gráfica de Monto Vendido por Mes -->
                <section class="col-lg-6 col-md-6 col-sm-12">
                    <div class="card card-teal">
                        <div class="card-header">
                            <h3 class="card-title">Monto Vendido por Mes</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                                <button type="button" class="btn btn-tool" data-card-widget="remove">
                                    <i class="fas fa-times"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            @if($montosVendidosPorMes->isEmpty())
                                <p>Sin datos disponibles</p>
                            @else
                                <div class="chart">
                                    <canvas id="areaChart"
                                        style="min-height: 250px; height: 250px; max-height: 250px; max-width: 100%; display: block; width: 100%;"></canvas>
                                </div>
                            @endif
                        </div>
                    </div>
                </section>
            </div>
        </div>
    </section>
@stop

@section('js')
    <script src="https://cdnjs.cloudflare.com/ajax/libs/Chart.js/3.7.0/chart.min.js"></script>
    <script>
        $(function() {
            @if(!$productosVendidosEnElMes->isEmpty())
                // Obtener los datos de productos y porcentajes
                var productos = {!! json_encode($productosVendidosEnElMes->pluck('producto')) !!};
                var porcentajes = {!! json_encode($productosVendidosEnElMes->pluck('porcentaje_total')) !!};

                // Configurar los datos para el gráfico de Donut
                var ctx = document.getElementById('donutChart');
                var myChart = new Chart(ctx, {
                    type: 'doughnut',
                    data: {
                        labels: productos,
                        datasets: [{
                            label: 'Porcentaje de Ventas',
                            data: porcentajes,
                            backgroundColor: [
                                'rgba(128, 0, 128, 0.5)', // Púrpura
                                'rgba(147, 112, 219, 0.5)', // Morado claro
                                'rgba(75, 0, 130, 0.5)', // Índigo oscuro
                                'rgba(186, 85, 211, 0.5)', // Orquídea oscura
                                // Añadir más colores si tienes más de 4 productos
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: '(Últimos 30 días)'
                            }
                        }
                    }
                });
            @endif

            @if(!$ventasPorUsuario->isEmpty())
                // Obtener los datos de usuarios y porcentajes de ventas
                var usuarios = {!! json_encode($ventasPorUsuario->pluck('nombre_usuario')) !!};
                var porcentajesVentas = {!! json_encode($ventasPorUsuario->pluck('porcentaje_ventas')) !!};

                // Configurar los datos para el gráfico de Donut 2
                var ctx2 = document.getElementById('donutChart2');
                var myChart2 = new Chart(ctx2, {
                    type: 'doughnut',
                    data: {
                        labels: usuarios,
                        datasets: [{
                            label: 'Porcentaje de Ventas por Usuario',
                            data: porcentajesVentas,
                            backgroundColor: [
                                'rgba(255, 0, 255, 0.5)', // pink
                                'rgba(199, 21, 133, 0.5)', // Medium violet red
                                'rgba(218, 112, 214, 0.5)', // Orchid
                                'rgba(238, 130, 238, 0.5)', // Violet
                                // Agrega más colores si es necesario
                            ],
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            legend: {
                                position: 'bottom',
                            },
                            title: {
                                display: true,
                                text: '(Últimos 30 días)'
                            }
                        }
                    }
                });
            @endif

            @if(!$montosVendidosPorMes->isEmpty())
                // Obtener los datos de montos vendidos por mes
                var meses = {!! json_encode($montosVendidosPorMes->pluck('mes')) !!};
                var montosVendidos = {!! json_encode($montosVendidosPorMes->pluck('monto_vendido')) !!};

                // Configurar los datos para la gráfica de área
                var ctx3 = document.getElementById('areaChart');
                var myChart3 = new Chart(ctx3, {
                    type: 'line',
                    data: {
                        labels: meses,
                        datasets: [{
                            label: '(Últimos 6 meses)',
                            data: montosVendidos,
                            borderColor: 'rgba(52, 211, 153, 1)', // Cambiado a un tono de verde
                            backgroundColor: 'rgba(52, 211, 153, 0.2)', // Cambiado a un tono de verde para el relleno
                            borderWidth: 1
                        }]
                    },
                    options: {
                        plugins: {
                            filler: {
                                propagate: true // Rellenar debajo de la línea
                            }
                        },
                        elements: {
                            line: {
                                tension: 0.4 // Curvatura de la línea
                            }
                        },
                        scales: {
                            y: {
                                beginAtZero: true
                            }
                        }
                    }
                });
            @endif
        });
    </script>
@stop
