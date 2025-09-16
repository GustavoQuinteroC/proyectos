<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Entrega;
use App\Models\Socio;
use Yajra\DataTables\DataTables;
use Carbon\Carbon;

class ComisionesRepartidorController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $fechaInicio = Carbon::createFromTimestamp($request->input('fechaInicio') / 1000)->startOfDay();
            $fechaFin = Carbon::createFromTimestamp($request->input('fechaFin') / 1000)->endOfDay();

            $data = Entrega::select('entregas.*', 'socios.name as socio')
                ->join('socios', 'entregas.idsocio', '=', 'socios.id')
                ->whereBetween('entregas.fecha', [$fechaInicio, $fechaFin])
                ->orderByDesc('entregas.fecha')
                ->get();

            // Suma condicional de costo_entrega solo para registros con status "Entregado"
            $totalCostoEntrega = Entrega::whereBetween('fecha', [$fechaInicio, $fechaFin])
                ->where('status', 'Entregado')
                ->sum('costo_entrega');

            return response()->json([
                'data' => $data,
                'totalCostoEntrega' => $totalCostoEntrega // Pasa la suma a la vista
            ]);
        }

        return view('sistema.comisiones.indexRepartidor');
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
