<?php

namespace App\Http\Controllers;

use App\Models\user;
use Illuminate\Http\Request;
use App\Http\Requests\StoreUsuarioRequest;
use App\Http\Requests\UpdateUsuarioRequest;
use Yajra\DataTables\DataTables;
class UsuarioController extends Controller
{
    /**
     * Display a listing of the resource.
     */

     public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = User::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('usuarios.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.usuarios.listado');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sistema.usuarios.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreUsuarioRequest $request)
    {
        $validacion = $request->validate([
            'name' => 'required|string|max:100',
            'email' => 'required|email|unique:users,email|max:100',
            'telefono' => 'required|numeric|min:1000000001|max:999999999999999',
            'ine' => 'nullable|string|max:20',
            'rfc' => 'nullable|string|max:25',
            'domicilio' => 'nullable|string|max:100',
            'password' => 'required|string|min:7|max:200,',

        ]);
        $usuario = new user();

        $usuario->name = $request->input('name');
        $usuario->email = $request->input('email');
        $usuario->telefono = $request->input('telefono');
        $usuario->ine = $request->input('ine');
        $usuario->domicilio = $request->input('domicilio');
        $usuario->rfc = $request->input('rfc');
        $usuario->password = $request->input('password');

        $usuario->save();
        return back()->with('message', 'ok');
    }

    /**
     * Display the specified resource.
     */
    public function show(user $usuario)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $usuario = user::find($id);

        return view('sistema.usuarios.consulta', compact('usuario'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateUsuarioRequest $request, User $usuario)
    {
        $validacion = $request->validate([
            'name' => 'required|string|max:100|unique:socios,name,' . $usuario->id,
            'email' => 'required|email|max:100|unique:users,email,' . $usuario->id,
            'telefono' => 'required|numeric|min:1000000001|max:999999999999999|unique:users,telefono,' . $usuario->id,
            'ine' => 'nullable|string|max:20|unique:users,ine,' . $usuario->id,
            'rfc' => 'nullable|string|max:25|unique:users,rfc,' . $usuario->id,
            'domicilio' => 'nullable|string|max:100|unique:users,domicilio,' . $usuario->id,
            'password' => 'nullable|string|min:7|max:200', // Quitamos la requerida de la contraseña

        ]);

        // Preparar los datos a actualizar
        $datosActualizados = [
            'name' => $request->input('name'),
            'email' => $request->input('email'),
            'telefono' => $request->input('telefono'),
            'domicilio' => $request->input('domicilio'),
            'ine' => $request->input('ine'),
            'rfc' => $request->input('rfc'),
        ];

        // Verificar si se proporcionó una nueva contraseña
        if ($request->filled('password')) {
            $datosActualizados['password'] = bcrypt($request->input('password'));
        }

        // Actualizar el usuario con los datos preparados
        $usuario->update($datosActualizados);

        return back()->with('message', 'ok');
    }


    /**
     * Remove the specified resource from storage.
     */

     public function destroy(user $usuario)
    {
        $usuario->delete();
        
        return response()->json(['success' => true]);
    }

    public function buscar(Request $request)
    {
        if ($request->ajax()) {
            $usuarios = User::where('name', 'like', '%' . $request->search . '%')
                ->get();

            $data = array();
            foreach ($usuarios as $usuario) {
                $data[] = array(
                    'label' => $usuario->name,
                    'value' => $usuario->name,
                    'id' => $usuario->id
                );
            }

            return response()->json($data);
        }
    }


}
