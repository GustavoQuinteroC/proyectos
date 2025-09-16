<?php

namespace App\Http\Controllers;

use App\Models\Socio;
use Illuminate\Http\Request;
use App\Http\Requests\StoreSocioRequest;
use App\Http\Requests\UpdateSocioRequest;
use Yajra\DataTables\DataTables;

class ClienteController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Socio::select('*')->whereIn('tipo', ['cliente', 'ambos']);
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('clientes.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.clientes.listado');
    }




    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sistema.clientes.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreSocioRequest $request)
    {
        $validacion = $request->validate([
            'name' => 'required|max:100|string|unique:socios,name',
            'email' => 'nullable|max:100|email|unique:socios,email',
            'telefono' => 'required|max:9999999999|numeric|unique:socios,telefono',
            'sexo' => 'nullable|max:10|string',
            'dias_entrega' => 'nullable|max:30|numeric',
            'plataforma' => 'nullable|max:10|string',
            'rfc' => 'nullable|max:25|string|unique:socios,rfc',
            'domicilio' => 'nullable|max:100|string',
        ]);

        $cliente = new Socio();
        $cliente->name = $request->input('name');
        $cliente->email = $request->input('email');
        $cliente->telefono = $request->input('telefono');
        $cliente->domicilio = $request->input('domicilio');
        $cliente->sexo = $request->input('sexo');
        $cliente->dias_entrega = $request->input('dias_entrega');
        $cliente->plataforma = $request->input('plataforma');
        $cliente->rfc = $request->input('rfc');
        $cliente->tipo = 'cliente';
        $cliente->save();


        return back()->with('message', 'ok');
    }



    /**
     * Display the specified resource.
     */
    public function show(Socio $cliente)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Socio $cliente)
    {
        return view('sistema.clientes.consulta', compact('cliente'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateSocioRequest $request, Socio $cliente)
    {
        $validacion = $request->validate([
            'name' => 'required|max:100|string|unique:socios,name,' . $cliente->id,
            'email' => 'nullable|max:100|email|unique:socios,email,' . $cliente->id,
            'telefono' => 'required|max:9999999999|numeric|unique:socios,telefono,' . $cliente->id,
            'sexo' => 'nullable|max:10|string',
            'dias_entrega' => 'nullable|max:30|numeric',
            'plataforma' => 'nullable|max:10|string',
            'rfc' => 'nullable|max:25|string|unique:socios,rfc,' . $cliente->id,
            'domicilio' => 'nullable|max:100|string',
        ]);

        $cliente->update([
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'domicilio' => $request->input('domicilio'),
            'sexo' => $request->input('sexo'),
            'dias_entrega' => $request->input('dias_entrega'),
            'plataforma' => $request->input('plataforma'),
            'rfc' => $request->input('rfc'),
            'tipo' => 'cliente',
        ]);

        return back()->with('message', 'ok');
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Socio $cliente)
    {
        $cliente->delete();

        return response()->json(['success' => true]);
    }

    public function buscar(Request $request)
    {
        if ($request->ajax()) {
            $clientes = Socio::where('name', 'like', '%' . $request->search . '%')
                ->whereIn('tipo', ['cliente', 'ambos'])
                ->get();

            $data = array();
            foreach ($clientes as $cliente) {
                $data[] = array(
                    'label' => $cliente->name,
                    'value' => $cliente->name,
                    'id' => $cliente->id,
                    'name' => $cliente->name,
                    'telefono' => $cliente->telefono,
                    'sexo' => $cliente->sexo
                );
            }

            return response()->json($data);
        }
    }






}
