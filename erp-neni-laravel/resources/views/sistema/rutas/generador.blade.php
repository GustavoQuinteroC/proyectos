<!-- resources/views/sistema/rutas/generador.blade.php -->
@extends('adminlte::page')

@section('title', 'Principal')

@section('content_header')
    <div class="d-flex justify-content-between align-items-center">
        <h1 class="m-0 text-dark">Generador de rutas (HOY)</h1>
    </div>
@stop

@section('content')
    <section class="content">
        <div id="map" style="height: 500px;"></div>
    </section>
@stop

@section('css')
    <link rel="stylesheet" href="https://unpkg.com/leaflet/dist/leaflet.css" />
@stop

@section('js')
    <script src="https://unpkg.com/leaflet/dist/leaflet.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/axios/dist/axios.min.js"></script>
    <script>
        // Inicializar el mapa
        var map = L.map('map').setView([20.6600287, -103.3393025], 11);

        // Añadir capa de OpenStreetMap
        L.tileLayer('https://{s}.tile.openstreetmap.org/{z}/{x}/{y}.png', {
            maxZoom: 18,
        }).addTo(map);

        // Datos de las entregas
        var entregas = @json($entregas);
        var markers = [];
        var waypoints = [];

        entregas.forEach(function(entrega) {
            if (entrega.latitud && entrega.longitud) {
                var marker = L.marker([entrega.latitud, entrega.longitud]).addTo(map);
                marker.bindPopup(`<b>${entrega.punto_name}</b><br>Status: ${entrega.status}<br>Cobrar: ${entrega.cobrar_cliente}<br>Costo: ${entrega.costo_entrega}`);
                markers.push(marker);
                waypoints.push([entrega.latitud, entrega.longitud]);
            }
        });

        document.getElementById('generar-ruta').addEventListener('click', function() {
            if (waypoints.length > 1) {
                var coordinates = waypoints.map(function(coord) {
                    return coord.reverse().join(',');
                }).join(';');

                var url = `http://router.project-osrm.org/route/v1/driving/${coordinates}?overview=full&geometries=geojson`;

                axios.get(url)
                    .then(function(response) {
                        var route = response.data.routes[0];

                        var routeCoords = L.geoJSON(route.geometry).getLayers()[0].getLatLngs();
                        var polyline = L.polyline(routeCoords, {color: 'blue'}).addTo(map);

                        map.fitBounds(polyline.getBounds());
                    })
                    .catch(function(error) {
                        console.error(error);
                    });
            } else {
                alert('Se necesitan al menos dos puntos para generar una ruta.');
            }
        });
    </script>
@stop
