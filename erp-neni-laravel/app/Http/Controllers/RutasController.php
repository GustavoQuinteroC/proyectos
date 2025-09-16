<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Entrega;
use App\Models\Puntos_entrega;
use App\Http\Requests\StoreEntregaRequest;
use App\Http\Requests\UpdateEntregaRequest;
use Illuminate\Support\Facades\DB;

class RutasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    // Entrega.php (Modelo)
    public function puntoEntrega()
    {
        return $this->belongsTo(Puntos_entrega::class, 'idpunto_entrega');
    }

    // PuntoEntrega.php (Modelo)
    public function entregas()
    {
        return $this->hasMany(Entrega::class, 'idpunto_entrega');
    }

    // RutaController.php (Controlador)
    

    public function index()
    {
        $hoy = Carbon::today()->toDateString();

        $entregas = DB::table('entregas')
            ->join('puntos_entregas', 'entregas.idpunto_entrega', '=', 'puntos_entregas.id')
            ->whereDate('entregas.fecha', $hoy)
            ->select('entregas.*', 'puntos_entregas.name as punto_name', 'puntos_entregas.latitud', 'puntos_entregas.longitud')
            ->get();

            return view('sistema.rutas.generador', compact('entregas'));
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
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateEntregaRequest $request, Entrega $entrega)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Entrega $entrega)
    {
        //
    }
}
