<?php

namespace App\Http\Controllers;

use App\Models\Puntos_entrega;
use App\Http\Requests\StorePuntos_entregaRequest;
use App\Http\Requests\UpdatePuntos_entregaRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class PuntosEntregaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Puntos_entrega::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<button type="button" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit" onclick="editarPuntoEntrega(' . $row->id . ')">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </button>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.puntos_entregas.listado');
    }


    public function edit(Puntos_entrega $puntos_entrega)
    {
        $punto_entrega = Puntos_entrega::findOrFail($puntos_entrega->id);
        return response()->json($punto_entrega);
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

    public function store(StorePuntos_entregaRequest $request)
    {
        $request->validate([
            'nombre' => 'required|string|max:255|unique:puntos_entregas,name',
            'clasificacion' => 'required|in:Tren,Macrobus',
            'linea' => 'required|string|max:255',
            'latitud' => 'nullable|string|max:255',
            'longitud' => 'nullable|string|max:255',
        ]);

        $puntoEntrega = new Puntos_entrega();
        $puntoEntrega->name = $request->input('nombre');
        $puntoEntrega->clasificacion = $request->input('clasificacion');
        $puntoEntrega->linea = $request->input('linea');
        $puntoEntrega->latitud = $request->input('latitud');
        $puntoEntrega->longitud = $request->input('longitud');
        $puntoEntrega->save();

        return redirect()->route('puntos_entregas.index')->with('guardado', 'ok');
    }

    /**
     * Display the specified resource.
     */
    public function show(Puntos_entrega $puntos_entrega)
    {
        //
    }






    /**
     * Update the specified resource in storage.
     */
    public function update(UpdatePuntos_entregaRequest $request, Puntos_entrega $puntos_entrega)
    {
        $request->validate([
            'nombre_edit' => 'required|string|max:255|unique:puntos_entregas,name,' . $puntos_entrega->id,
            'clasificacion_edit' => 'required|in:Tren,Macrobus',
            'linea_edit' => 'required|string|max:255',
            'latitud_edit' => 'nullable|string|max:255',
            'longitud_edit' => 'nullable|string|max:255',
        ]);

        $puntos_entrega->update([
            'name' => $request->input('nombre_edit'),
            'clasificacion' => $request->input('clasificacion_edit'),
            'linea' => $request->input('linea_edit'),
            'latitud' => $request->input('latitud_edit'),
            'longitud' => $request->input('longitud_edit'),
        ]);

        if ($request->ajax()) {
            return response()->json(['success' => true]);
        }

        return redirect()->route('puntos_entregas.index')->with('actualizado', 'ok');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Puntos_entrega $puntos_entrega)
    {
        //
    }

    public function buscar(Request $request)
    {
        if ($request->ajax()) {
            $puntosEntrega = Puntos_entrega::where('name', 'like', '%' . $request->search . '%')->get();

            $data = array();
            foreach ($puntosEntrega as $puntoEntrega) {
                $data[] = array(
                    'label' => $puntoEntrega->name,
                    'value' => $puntoEntrega->name,
                    'id' => $puntoEntrega->id,
                    // Agrega aquí cualquier otro dato adicional que desees incluir en el autocompletado
                );
            }

            return response()->json($data);
        }
    }


}
