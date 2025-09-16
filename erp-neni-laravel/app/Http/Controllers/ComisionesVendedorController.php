<?php

namespace App\Http\Controllers;

use App\Models\Venta;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ComisionesVendedorController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
{
    if ($request->ajax()) {
        $fechaInicio = Carbon::createFromTimestamp($request->input('fechaInicio') / 1000)->startOfDay();
        $fechaFin = Carbon::createFromTimestamp($request->input('fechaFin') / 1000)->endOfDay();

        $data = Venta::select(
            'ventas.*',
            'socios.name as socio',
            'entregas.status',
            'entregas.fecha as fechaEntrega',
            DB::raw('(entregas.cobrar_cliente - entregas.costo_entrega) * 0.15 as comision')
        )
            ->join('entregas', 'entregas.id', '=', 'ventas.identrega')
            ->join('socios', 'entregas.idsocio', '=', 'socios.id')
            ->whereBetween('entregas.fecha', [$fechaInicio, $fechaFin])
            ->orderByDesc('entregas.fecha')
            ->get();

        $totalComisiones = $data->filter(function ($item) {
            return $item->status == 'Entregado';
        })->sum('comision'); // Filtra solo los registros con status "Entregado" y calcula la suma de las comisiones

        return response()->json([
            'data' => $data,
            'totalComisiones' => $totalComisiones // Pasa la suma a la vista
        ]);
    }

    return view('sistema.comisiones.indexVendedor');
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
