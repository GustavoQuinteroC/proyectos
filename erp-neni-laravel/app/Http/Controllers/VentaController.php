<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use App\Models\Socio;
use App\Models\User;
use App\Models\Almacene;
use App\Models\Ventas_producto;
use App\Models\Puntos_entrega;
use App\Models\Entrega;
use App\Models\Movimiento;
use App\Models\Movimientos_producto;
use Illuminate\Http\Request;
use App\Http\Requests\StoreVentaRequest;
use App\Http\Requests\UpdateVentaRequest;
use Illuminate\Support\Facades\Auth;
use Yajra\DataTables\DataTables;

class VentaController extends Controller
{
    /**
     * Display a listing of the resource.
     */




    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Venta::select('ventas.*', 'socios.name as socio', 'users.name as usuarioEntrega', 'socios.telefono', 'entregas.id as identrega', 'entregas.fecha as fechaEntrega', 'entregas.status as statusEntrega')
                ->join('entregas', 'ventas.identrega', '=', 'entregas.id')
                ->join('users', 'ventas.idusuario', '=', 'users.id')
                ->join('socios', 'ventas.idsocio', '=', 'socios.id');

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $socioName = $row->socio;
                    $whatsappLink = 'https://wa.me/' . $row->telefono . '?text=' . urlencode('Hola ' . $socioName . ', soy la persona que te vendió el smartwatch por Facebook. El mensajero está intentando comunicarse contigo pero no ha tenido éxito. ¿Podrías responderle?');
                    $btnWhatsapp = '<a href="' . $whatsappLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Send WhatsApp">
                                <i class="fa fa-lg fa-fw fa-comments"></i>
                                </a>';
                    $telLink = 'tel:' . $row->telefono;
                    $btnPhone = '<a href="' . $telLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Call">
                             <i class="fa fa-lg fa-fw fa-phone"></i>
                             </a>';
                    $btnEdit = '<a href="' . route('ventas.show', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                                </a>';
                    return $btnWhatsapp . $btnPhone . $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.ventas.listado');
    }


    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $almacenes = Almacene::all();
        $productos = []; // Aquí deberías obtener los datos de las ventas desde tu base de datos o cualquier otra fuente
        return view('sistema.ventas.nuevo', compact('almacenes', 'productos'));
    }



    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreVentaRequest $request)
    {
        // Validar los datos del request
        $validacion = $request->validate([
            'telefono' => 'required|numeric|digits:10',
            'idalmacen' => 'required|exists:almacenes,id',
            'forma_pago' => 'required',
            'nombre_cliente' => 'required|string',
            'nombre_punto' => 'required',
            'nombre_usuario' => 'required',
            'costo_entrega' => 'required',
            'idpunto_entrega' => 'required|exists:puntos_entregas,id',
            'fecha' => 'required|date|after_or_equal:today',
            'hora' => 'required',
            'total_hidden' => 'required|numeric|gt:0',
        ], [
            'idpunto_entrega.exists' => 'El campo nombre_punto es inválido.',
        ]);


        if (is_null($request->idsocio)) {
            $cliente = new Socio();
            $cliente->name = $request->nombre_cliente;
            $cliente->telefono = $request->telefono;
            $cliente->tipo = 'cliente';
            $cliente->save();
            $idcliente = $cliente->id;
        } else {
            $idcliente = $request->idsocio;
        }

        $entrega = new Entrega();
        $entrega->idusuario = $request->idusuario;
        $entrega->idpunto_entrega = $request->idpunto_entrega;
        $entrega->idsocio = $idcliente;
        $entrega->fecha = $request->fecha;
        $entrega->hora = $request->hora;
        $entrega->notas = '';
        $entrega->cobrar_cliente = $request->total_hidden;
        $entrega->costo_entrega = $request->costo_entrega;
        $entrega->save();

        $venta = new Venta();
        $venta->idsocio = $idcliente;
        $venta->idalmacen = $request->idalmacen;
        $venta->identrega = $entrega->id;
        $venta->notas = $request->notas_entrega;
        $venta->idusuario = Auth::id();
        $venta->forma_pago = $request->forma_pago;
        $venta->cuenta_pago = $request->cuenta_pago;
        $venta->total = $request->total_hidden;
        $venta->save();

        // Crear el movimiento de almacen
        $movimiento = new Movimiento();
        $movimiento->idsocio = $idcliente;
        $movimiento->idalmacen = $request->idalmacen;
        $movimiento->idusuario = Auth::id();
        $movimiento->idtipo = 2;
        $movimiento->referencia = '';
        $movimiento->notas = $request->notas;
        $movimiento->save();


        $productosSeleccionados = json_decode($request->productos_seleccionados, true);
        foreach ($productosSeleccionados as $producto) {
            // Guardar los productos seleccionados en la tabla recepciones_productos
            $ventaProducto = new Ventas_producto();
            $ventaProducto->idproducto = $producto['id'];
            $ventaProducto->preciou = $producto['precio'];
            $ventaProducto->cantidad = $producto['cantidad'];
            $ventaProducto->idventa = $venta->id;
            $ventaProducto->desc_porcentaje = 0;
            $ventaProducto->save();

            // Guardar los productos seleccionados en la tabla movimientos_producto
            $mov_pda = new Movimientos_producto();
            $mov_pda->idmovimiento = $movimiento->id;
            $mov_pda->idproducto = $producto['id'];
            $mov_pda->cantidad = $producto['cantidad'];
            $mov_pda->tabla_ref = 'ventas_productos';
            $mov_pda->idtabla_ref = $ventaProducto->id;
            $mov_pda->save();
        }


        // Mostrar un mensaje de éxito
        return back()->with('message', 'ok');
    }

    /**
     * Display the specified resource.
     */
    public function show(Venta $venta)
    {
        $productos = Ventas_producto::select('ventas_productos.*', 'productos.catalogo as catalogo')
            ->join('productos', 'productos.id', '=', 'ventas_productos.idproducto')
            ->where('ventas_productos.idventa', $venta->id)
            ->get();

        // Obtener el concepto asociado al movimiento
        $almacen = Almacene::find($venta->idalmacen);
        // Obtener el concepto asociado al movimiento
        $entrega = Entrega::find($venta->identrega);
        // Obtener el concepto asociado al movimiento
        $punto_entrega = Puntos_entrega::find($venta->identrega);
        // Obtener el concepto asociado al movimiento
        $usuario = User::find($venta->idusuario);
        // Obtener el concepto asociado al movimiento
        $socio = Socio::find($venta->idsocio);


        return view('sistema.ventas.ver', compact('venta', 'productos', 'socio', 'almacen', 'entrega', 'usuario', 'punto_entrega'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Venta $venta)
    {
        return view('sistema.ventas.consulta', compact('venta'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateVentaRequest $request, Venta $venta)
    {
        /*$validacion = $request->validate([
            'name' => 'required|max:100|string|unique:socios,name,' . $socio->id,
            'email' => 'nullable|max:100|email|unique:socios,email,' . $socio->id,
            'telefono' => 'required|max:9999999999|numeric|unique:socios,telefono,' . $socio->id,
            'sexo' => 'nullable|max:10|string',
            'dias_entrega' => 'nullable|max:30|numeric',
            'plataforma' => 'nullable|max:10|string',
            'rfc' => 'nullable|max:25|string|unique:socios,rfc,' . $socio->id,
            'domicilio' => 'nullable|max:100|string',
        ]);

        $socio->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'domicilio' => $request->input('domicilio'),
            'sexo' => $request->input('sexo'),
            'dias_entrega' => $request->input('dias_entrega'),
            'plataforma' => $request->input('plataforma'),
            'rfc' => $request->input('rfc'),
        ]);

        return back()->with('message', 'ok');*/
    }

    /*public function update(UpdateVentaRequest $request, $id)
    {
        $venta = Socio::find($id);
        $venta->name = $request->input('name');
        $venta->save();

        return response()->json(['success' => true]);
    }*/

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Venta $venta)
    {
        $venta->delete();
        return back();
    }
}
