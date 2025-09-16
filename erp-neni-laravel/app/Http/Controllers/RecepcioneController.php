<?php

namespace App\Http\Controllers;

use App\Models\Recepcione;
use App\Models\Recepciones_producto;
use App\Models\Movimiento;
use App\Models\Movimientos_producto;
use App\Models\Almacene;
use App\Models\Socio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreRecepcioneRequest;
use App\Http\Requests\UpdateRecepcioneRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Auth;

class RecepcioneController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Recepcione::select('recepciones.*', 'almacenes.name as almacen', 'socios.name as socio')
                ->join('almacenes', 'recepciones.idalmacen', '=', 'almacenes.id')
                ->join('socios', 'recepciones.idsocio', '=', 'socios.id');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('recepciones.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                            <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.recepciones.listado');
    }


    /**
     * Show the form for creating a new resource.
     */

    public function create()
    {
        $almacenes = Almacene::all();
        $productos = []; // Aquí deberías obtener los datos de las ventas desde tu base de datos o cualquier otra fuente
        return view('sistema.recepciones.nuevo', compact('almacenes', 'productos'));
    }

    /**
     * Store a newly created resource in storage.
     */




    public function store(StoreRecepcioneRequest $request)
    {
        // Guardar los datos principales en la tabla recepciones
        $recepcion = new Recepcione();
        $recepcion->idsocio = $request->selectedSocio;
        $recepcion->idalmacen = $request->idalmacen;
        $recepcion->forma_pago = $request->forma_pago;
        $recepcion->cuenta_pago = $request->cuenta_pago;
        $recepcion->fecha_recepcion = $request->fecha;
        $recepcion->guia = $request->guia;
        $recepcion->referencia = $request->referencia;
        $recepcion->costos_extras = $request->costos_extras;
        $recepcion->idusuario = Auth::id();
        $recepcion->total = $request->total_hidden;
        $recepcion->save();

        // Crear el movimiento de almacen
        $movimiento = new Movimiento();
        $movimiento->idsocio = $request->selectedSocio;
        $movimiento->idalmacen = $request->idalmacen;
        $movimiento->idusuario = Auth::id();
        $movimiento->idtipo = 1;
        $movimiento->referencia = $request->referencia;
        $movimiento->notas = '';
        $movimiento->save();

        $productosSeleccionados = json_decode($request->productos_seleccionados, true);
        foreach ($productosSeleccionados as $producto) {
            // Guardar los productos seleccionados en la tabla recepciones_productos
            $recepcionProducto = new Recepciones_producto();
            $recepcionProducto->idproducto = $producto['id'];
            $recepcionProducto->costo = $producto['costo'];
            $recepcionProducto->cantidad = $producto['cantidad'];
            $recepcionProducto->idrecepcion = $recepcion->id;
            $recepcionProducto->save();

            // Guardar los productos seleccionados en la tabla movimientos_producto
            $mov_pda = new Movimientos_producto();
            $mov_pda->idmovimiento = $movimiento->id;
            $mov_pda->idproducto = $producto['id'];
            $mov_pda->cantidad = $producto['cantidad'];
            $mov_pda->tabla_ref = 'recepciones_productos';
            $mov_pda->idtabla_ref = $recepcionProducto->id;
            $mov_pda->save();
        }

        // Mostrar un mensaje de éxito
        return back()->with('message', 'ok');
    }




    /**
     * Display the specified resource.
     */
    public function show(Recepcione $recepcione)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Recepcione $recepcione)
    {
        // Obtener la lista de productos asociados a la recepción
        $productos = Recepciones_producto::join('productos', 'recepciones_productos.idproducto', '=', 'productos.id')
            ->select('recepciones_productos.id', 'productos.catalogo', 'productos.descripcion', 'recepciones_productos.costo', 'recepciones_productos.cantidad')
            ->where('recepciones_productos.idrecepcion', $recepcione->id)
            ->get();

        // Obtener el socio asociado a la recepción
        $socio = Socio::find($recepcione->idsocio);

        // Obtener el almacén asociado a la recepción
        $almacen = Almacene::find($recepcione->idalmacen);

        // Retornar la vista de consulta con los datos de la recepción y los productos asociados
        return view('sistema.recepciones.consulta', compact('recepcione', 'productos', 'socio', 'almacen'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateRecepcioneRequest $request, Recepcione $recepcione)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Recepcione $recepcione)
    {
        //
    }
}
