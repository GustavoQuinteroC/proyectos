<?php

namespace App\Http\Controllers;

use App\Models\Almacene;
use App\Models\User;
use App\Models\Almacen_producto;
use App\Models\Producto;
use App\Http\Requests\StoreAlmaceneRequest;
use App\Http\Requests\UpdateAlmaceneRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class AlmaceneController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Almacene::join('users', 'almacenes.idusuario_encargado', '=', 'users.id')
                ->select('almacenes.*', 'users.name as nombre_usuario');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('almacenes.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.almacenes.listado');
    }



    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $productos = [];
        return view('sistema.almacenes.nuevo')->with('productos', $productos);
    }


    /**
     * Store a newly created resource in storage.
     */


    public function store(StoreAlmaceneRequest $request)
    {
        // Validar los datos del almacén
        $request->validate([
            'name' => 'required|unique:almacenes',
            'direccion' => 'required',
            'idusuario' => 'required|exists:users,id', // Ajusta el nombre de la tabla de usuarios según tu estructura
            'capacidad' => 'required|numeric',
        ]);

        // Crear un nuevo almacén
        $almacen = new Almacene();
        $almacen->name = $request->input('name');
        $almacen->direccion = $request->input('direccion');
        $almacen->idusuario_encargado = $request->input('idusuario');
        $almacen->capacidad_m3 = $request->input('capacidad');
        $almacen->save();


        // Recuperar los IDs de los productos seleccionados
        $productosSeleccionados = json_decode($request->input('productos_seleccionados'));

        // Guardar los productos asociados al almacén
        foreach ($productosSeleccionados as $productoId) {
            $almacenProducto = new Almacen_producto();
            $almacenProducto->idproducto = $productoId;
            $almacenProducto->idalmacen = $almacen->id;
            $almacenProducto->existencia = 0; // existencia inicial del producto en el almacén
            $almacenProducto->save();
        }

        // Mostrar un mensaje de éxito
        return back()->with('message', 'ok');
    }





    /**
     * Display the specified resource.
     */
    public function show(Almacene $almacene)
    {

    }


    public function edit(Almacene $almacene)
    {
        // Obtener la lista de productos asociados al almacén con la información completa
        $productos = Almacen_producto::join('productos', 'almacen_productos.idproducto', '=', 'productos.id')
            ->select('almacen_productos.id as almacen_producto_id', 'productos.id', 'productos.catalogo', 'productos.descripcion') // Seleccionar los campos deseados
            ->where('almacen_productos.idalmacen', $almacene->id)
            ->get();

        // Obtener el usuario asociado al almacen
        $usuario = User::where('id', $almacene->idusuario_encargado)->first();

        // Retornar la vista de edición con los datos del almacén y los productos asociados
        return view('sistema.almacenes.consulta', compact('almacene', 'productos', 'usuario'));
    }



    /**
     * Update the specified resource in storage.
     */


    public function update(UpdateAlmaceneRequest $request, Almacene $almacene)
    {
        // Validar los datos del almacén
        $request->validate([
            'name' => 'required|min:3|max:40|string|unique:almacenes,name,' . $almacene->id,
            'direccion' => 'required',
            'idusuario' => 'required|exists:users,id',
            'capacidad' => 'nullable|numeric',
        ]);

        // Actualizar información del almacén
        $almacene->update([
            'name' => $request->input('name'),
            'direccion' => $request->input('direccion'),
            'idusuario_encargado' => $request->input('idusuario'),
            'capacidad_m3' => $request->input('capacidad'),
        ]);

        // Verificar si hay productos seleccionados
        $productosSeleccionados = $request->input('productos_seleccionados');
        if (!empty ($productosSeleccionados)) {
            // Recuperar los IDs de los nuevos productos seleccionados
            $productosSeleccionadosNuevos = json_decode($productosSeleccionados);

            // Guardar los productos asociados al almacén
            foreach ($productosSeleccionadosNuevos as $productoId) {
                $almacenProducto = new Almacen_producto();
                $almacenProducto->idproducto = $productoId;
                $almacenProducto->idalmacen = $almacene->id;
                $almacenProducto->existencia = 0; // existencia inicial del producto en el almacén
                $almacenProducto->save();
            }
        }

        // Mostrar un mensaje de éxito
        return back()->with('message', 'ok');
    }




    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Almacene $almacene)
    {
        //
    }
}
