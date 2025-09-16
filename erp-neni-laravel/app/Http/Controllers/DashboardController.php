<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Models\Venta;
use App\Models\Ventas_producto;
use App\Models\Almacen_producto;
use Illuminate\Support\Facades\DB;
use Illuminate\Http\Request;


class DashboardController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function __construct()
    {
        $this->middleware('auth');
    }
    public function index()
    {
        // Consulta para obtener las ventas y porcentajes de cada usuario
        $ventasPorUsuario = DB::table('users')
            ->leftJoin('ventas', 'users.id', '=', 'ventas.idusuario')
            ->leftJoin('ventas_productos', 'ventas.id', '=', 'ventas_productos.idventa')
            ->select(
                'users.id AS id_usuario',
                'users.name AS nombre_usuario',
                DB::raw('COALESCE(SUM(ventas_productos.preciou * ventas_productos.cantidad), 0) AS ventas_pesos'),
                DB::raw('ROUND((COALESCE(SUM(ventas_productos.preciou * ventas_productos.cantidad), 0) / 
                COALESCE((SELECT SUM(ventas_productos.preciou * ventas_productos.cantidad)
                          FROM ventas_productos
                          INNER JOIN ventas ON ventas_productos.idventa = ventas.id), 1)) * 100, 2) AS porcentaje_ventas')
            )
            ->groupBy('users.id', 'users.name')
            ->get();

        // Obtener los productos vendidos en los últimos 30 días y sus cantidades y porcentajes
        $productosVendidosEnElMes = Producto::join('ventas_productos', 'productos.id', '=', 'ventas_productos.idproducto')
            ->select(
                'productos.id',
                'productos.catalogo AS producto',
                DB::raw('SUM(ventas_productos.cantidad) AS total_vendido'),
                DB::raw('(SELECT SUM(cantidad) FROM ventas_productos WHERE created_at >= NOW() - INTERVAL 30 DAY) AS total_ventas'),
                DB::raw('ROUND((SUM(ventas_productos.cantidad) / (SELECT SUM(cantidad) FROM ventas_productos WHERE created_at >= NOW() - INTERVAL 30 DAY)) * 100, 2) AS porcentaje_total')
            )
            ->where('ventas_productos.created_at', '>=', DB::raw('NOW() - INTERVAL 30 DAY'))
            ->groupBy('productos.id', 'productos.catalogo')
            ->orderBy('total_vendido', 'DESC')
            ->get();

        // Montos vendidos por mes de los últimos 6 meses
        $montosVendidosPorMes = DB::table('ventas')
            ->select(
                DB::raw('MONTH(created_at) AS mes'),
                DB::raw('YEAR(created_at) AS año'),
                DB::raw('SUM(total) AS monto_vendido')
            )
            ->where('created_at', '>=', DB::raw('CURDATE() - INTERVAL 6 MONTH'))
            ->groupBy(DB::raw('YEAR(created_at)'), DB::raw('MONTH(created_at)'))
            ->orderBy(DB::raw('YEAR(created_at)'), 'ASC')
            ->orderBy(DB::raw('MONTH(created_at)'), 'ASC')
            ->get();

        // Encontrar el producto con el mayor porcentaje de ventas en el mes
        $productoMayorPorcentaje = $productosVendidosEnElMes->sortByDesc('porcentaje_total')->first();

        // Consultar la existencia total de productos del mismo tipo que el más vendido en el mes
        $existenciaProductoMayorPorcentaje = $productoMayorPorcentaje ? DB::table('almacen_productos')
            ->join('productos', 'productos.id', '=', 'almacen_productos.idproducto')
            ->select('productos.catalogo AS producto', DB::raw('SUM(almacen_productos.existencia) AS total_existencia'))
            ->where('almacen_productos.idproducto', $productoMayorPorcentaje->id)
            ->groupBy('almacen_productos.idproducto', 'productos.catalogo') // Agrupar por el ID del producto y el nombre del producto
            ->first() : null;

        $comisionesVendedor = Venta::join('entregas', function ($join) {
            $join->on('entregas.id', '=', 'ventas.identrega')
                ->where('entregas.status', '=', 'Entregado');
        })
            ->where('ventas.created_at', '>=', now()->subDays(30))
            ->selectRaw('SUM((ventas.total - entregas.costo_entrega) * 0.12) AS comisiones')
            ->first();

        $comisionesVendedor = $comisionesVendedor ? $comisionesVendedor->comisiones : 0;

        $comisionesRepartidor = Venta::join('entregas', function ($join) {
            $join->on('entregas.id', '=', 'ventas.identrega')
                ->where('entregas.status', '=', 'Entregado');
        })
            ->where('ventas.created_at', '>=', now()->subDays(30))
            ->selectRaw('SUM((ventas.total - entregas.costo_entrega) * 0.15) AS comisiones')
            ->first();

        $comisionesRepartidor = $comisionesRepartidor ? $comisionesRepartidor->comisiones : 0;

        $totalVentas = Venta::join('entregas', function ($join) {
            $join->on('entregas.id', '=', 'ventas.identrega')
                ->where('entregas.status', '=', 'Entregado');
        })
            ->where('ventas.created_at', '>=', now()->subDays(30))
            ->selectRaw('COUNT(ventas.id) AS totalVentas')
            ->first();

        $totalVentas = $totalVentas ? $totalVentas->totalVentas : 0;

        // Pasar los datos a la vista
        return view('home', compact('productosVendidosEnElMes', 'ventasPorUsuario', 'montosVendidosPorMes', 'existenciaProductoMayorPorcentaje', 'comisionesVendedor', 'comisionesRepartidor', 'totalVentas'));
    }






    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
