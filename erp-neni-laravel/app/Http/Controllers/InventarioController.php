<?php

namespace App\Http\Controllers;

use App\Models\Almacen_producto;
use App\Models\Almacene;
use App\Models\Producto;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

//use App\Http\Requests\StoreMovimientoRequest;
//use App\Http\Requests\UpdateMovimientoRequest;

class InventarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        // Obtener todos los almacenes
        $almacenes = Almacene::all();

        // Inicializar variables para almacenar el ID del almacén seleccionado y los productos asociados
        $almacen_id = null;
        $productos = [];

        // Verificar si se ha seleccionado un almacén
        if ($request->has('almacen_id')) {
            // Obtener el ID del almacén seleccionado
            $almacen_id = $request->almacen_id;

            // Obtener los productos asociados al almacén seleccionado junto con su existencia
            $productos = Producto::join('almacen_productos', 'productos.id', '=', 'almacen_productos.idproducto')
                ->where('almacen_productos.idalmacen', $almacen_id)
                ->select('productos.*', 'almacen_productos.existencia')
                ->get();
        }

        // Retornar la vista con los datos necesarios
        return view('sistema.inventarios.listado', compact('almacenes', 'almacen_id', 'productos'));
    }

    public function show(Almacen_producto $movimiento)
    {
        //
    }

}
