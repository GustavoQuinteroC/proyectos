<?php

namespace App\Http\Controllers;

use App\Models\Entrega;
use App\Models\User;
use App\Models\Ventas_producto;
use App\Models\Venta;
use App\Models\Almacene;
use App\Models\Puntos_entrega;
use App\Models\Socio;
use App\Http\Requests\StoreEntregaRequest;
use App\Http\Requests\UpdateEntregaRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;
use Carbon\Carbon;

class EntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Entrega::select('entregas.*', 'ventas.id as idventa', 'socios.name as socio', 'socios.telefono', 'puntos_entregas.clasificacion', 'puntos_entregas.linea', 'puntos_entregas.name as punto_entrega')
                ->join('puntos_entregas', 'entregas.idpunto_entrega', '=', 'puntos_entregas.id')
                ->join('ventas', 'entregas.id', '=', 'ventas.identrega')
                ->join('socios', 'entregas.idsocio', '=', 'socios.id')
                ->orderByDesc('entregas.fecha')
                ->get(); // Obtener los datos como una colección para poder manipularlos con Carbon

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $horaEntrega = Carbon::parse($row->hora)->format('h:i A'); // Convertir la hora a formato de 12 horas
                    $whatsappLink = 'https://wa.me/' . $row->telefono . '?text=' . urlencode('Hola ' . $row->socio . ', buen día. Soy el mensajero que le hará la entrega de su(s) smartwatch, me comunico para confirmar su entrega el día de hoy en ' . $row->clasificacion . ' - ' . $row->linea . ', estación ' . $row->punto_entrega . ' a las ' . $horaEntrega);
                    $btnWhatsapp = '<a href="' . $whatsappLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Send WhatsApp">
                             <i class="fa fa-lg fa-fw fa-comments"></i>
                             </a>';
                    $telLink = 'tel:' . $row->telefono;
                    $btnPhone = '<a href="' . $telLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Call">
                             <i class="fa fa-lg fa-fw fa-phone"></i>
                             </a>';
                    $btnEdit = '<a href="' . route('entregas.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                             <i class="fa fa-lg fa-fw fa-pen"></i>
                             </a>';
                    return $btnWhatsapp . $btnPhone . $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.entregas.listado');
    }

    public function indexhoy(Request $request)
    {
        if ($request->ajax()) {
            $data = Entrega::select('entregas.*', 'ventas.id as idventa', 'socios.name as socio', 'socios.telefono', 'puntos_entregas.clasificacion', 'puntos_entregas.linea', 'puntos_entregas.name as punto_entrega')
                ->join('puntos_entregas', 'entregas.idpunto_entrega', '=', 'puntos_entregas.id')
                ->join('ventas', 'entregas.id', '=', 'ventas.identrega')
                ->join('socios', 'entregas.idsocio', '=', 'socios.id')
                ->whereDate('entregas.fecha', Carbon::today())
                ->orderByDesc('entregas.fecha')
                ->get(); // Obtener los datos como una colección para poder manipularlos con Carbon

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $horaEntrega = Carbon::parse($row->hora)->format('h:i A'); // Convertir la hora a formato de 12 horas
                    $whatsappLink = 'https://wa.me/' . $row->telefono . '?text=' . urlencode('Hola ' . $row->socio . ', buen día. Soy el mensajero que le hará la entrega de su(s) smartwatch, me comunico para confirmar su entrega el día de hoy en ' . $row->clasificacion . ' - ' . $row->linea . ', estación ' . $row->punto_entrega . ' a las ' . $horaEntrega);
                    $btnWhatsapp = '<a href="' . $whatsappLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Send WhatsApp">
                             <i class="fa fa-lg fa-fw fa-comments"></i>
                             </a>';
                    $telLink = 'tel:' . $row->telefono;
                    $btnPhone = '<a href="' . $telLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Call">
                             <i class="fa fa-lg fa-fw fa-phone"></i>
                             </a>';
                    $btnEdit = '<a href="' . route('entregas.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                             <i class="fa fa-lg fa-fw fa-pen"></i>
                             </a>';
                    return $btnWhatsapp . $btnPhone . $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.entregas.listadohoy');
    }
    public function indexmanana(Request $request)
    {
        if ($request->ajax()) {
            $data = Entrega::select('entregas.*', 'ventas.id as idventa', 'socios.name as socio', 'socios.telefono', 'puntos_entregas.clasificacion', 'puntos_entregas.linea', 'puntos_entregas.name as punto_entrega')
                ->join('puntos_entregas', 'entregas.idpunto_entrega', '=', 'puntos_entregas.id')
                ->join('ventas', 'entregas.id', '=', 'ventas.identrega')
                ->join('socios', 'entregas.idsocio', '=', 'socios.id')
                ->whereDate('entregas.fecha', Carbon::tomorrow()) // Filtrar por la fecha de mañana
                ->orderByDesc('entregas.fecha')
                ->get(); // Obtener los datos como una colección para poder manipularlos con Carbon

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $horaEntrega = Carbon::parse($row->hora)->format('h:i A'); // Convertir la hora a formato de 12 horas
                    $whatsappLink = 'https://wa.me/' . $row->telefono . '?text=' . urlencode('Hola ' . $row->socio . ', buen día. Soy el mensajero que le hará la entrega de su(s) smartwatch, me comunico para confirmar su entrega el día de hoy en ' . $row->clasificacion . ' - ' . $row->linea . ', estación ' . $row->punto_entrega . ' a las ' . $horaEntrega);
                    $btnWhatsapp = '<a href="' . $whatsappLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Send WhatsApp">
                             <i class="fa fa-lg fa-fw fa-comments"></i>
                             </a>';
                    $telLink = 'tel:' . $row->telefono;
                    $btnPhone = '<a href="' . $telLink . '" class="btn btn-xs btn-default text-success mx-1 shadow" title="Call">
                             <i class="fa fa-lg fa-fw fa-phone"></i>
                             </a>';
                    $btnEdit = '<a href="' . route('entregas.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                             <i class="fa fa-lg fa-fw fa-pen"></i>
                             </a>';
                    return $btnWhatsapp . $btnPhone . $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.entregas.listadomanana');
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
    public function store(StoreEntregaRequest $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Entrega $entrega)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Entrega $entrega)
    {
        $venta = Venta::where('identrega', $entrega->id)->first();
        $productos = Ventas_producto::select('ventas_productos.*', 'productos.catalogo as catalogo')
            ->join('productos', 'productos.id', '=', 'ventas_productos.idproducto')
            ->where('ventas_productos.idventa', $venta->id)
            ->get();
        // Obtener el concepto asociado al movimiento
        $almacen = Almacene::find($venta->idalmacen);
        // Obtener el punto de entrega
        $punto_entrega = Puntos_entrega::find($entrega->idpunto_entrega);
        // Obtener el concepto asociado al movimiento
        $usuario = User::find($venta->idusuario);
        // Obtener el concepto asociado al movimiento
        $socio = Socio::find($venta->idsocio);

        // Retornar la vista de edición con los datos del almacén y los productos asociados
        return view('sistema.entregas.consulta', compact('venta', 'productos', 'socio', 'almacen', 'entrega', 'usuario', 'punto_entrega'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntregaRequest $request, Entrega $entrega)
    {
        $entrega->update([
            'status' => $request->input('estado'),
            'notas' => $request->input('notas_mensajero'),
        ]);
        return back()->with('message', 'ok');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrega $entrega)
    {
        //
    }

}
