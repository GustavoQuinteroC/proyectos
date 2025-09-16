<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use App\Http\Requests\StoreProductoRequest;
use App\Http\Requests\UpdateProductoRequest;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if ($request->ajax()) {
            $data = Producto::select('*');
            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', function ($row) {
                    $btnEdit = '<a href="' . route('productos.edit', $row->id) . '" class="btn btn-xs btn-default text-primary mx-1 shadow" title="Edit">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>';
                    return $btnEdit;
                })
                ->rawColumns(['action'])
                ->make(true);
        }

        return view('sistema.productos.listado');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('sistema.productos.nuevo');
    }

    /**
     * Store a newly created resource in storage.
     */

    public function store(StoreProductoRequest $request)
    {
        $validacion = $request->validate([
            'catalogo' => 'required|min:3|max:40|string|unique:productos,catalogo',
            'descripcion' => 'nullable|string',
            'precio' => 'required|max:9999999999|numeric',
            'costo' => 'required|max:9999999999|numeric',
        ]);

        $socio = new Producto();
        $socio->catalogo = $request->input('catalogo');
        $socio->descripcion = $request->input('descripcion');
        $socio->precio = $request->input('precio');
        $socio->costo = $request->input('costo');
        $socio->save();


        return back()->with('message', 'ok');
    }

    /**
     * Display the specified resource.
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */

    public function edit(Producto $producto)
    {
        return view('sistema.productos.consulta', compact('producto'));
    }


    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateProductoRequest $request, Producto $producto)
    {
        $validacion = $request->validate([
            'catalogo' => 'required|min:3|max:40|string|unique:productos,catalogo,' . $producto->id,
            'descripcion' => 'nullable|string',
            'precio' => 'required|max:9999999999|numeric',
            'costo' => 'required|max:9999999999|numeric',
        ]);

        $producto->update([
            'catalogo' => $request->input('catalogo'),
            'descripcion' => $request->input('descripcion'),
            'precio' => $request->input('precio'),
            'costo' => $request->input('costo'),
        ]);

        return back()->with('message', 'ok');
    }

    /**
     * Remove the specified resource from storage.
     */

    public function destroy(Producto $producto)
    {
        $producto->delete();

        return response()->json(['success' => true]);
    }

    public function getAllProducts()
    {
        $allproductos = Producto::all();
        return response()->json($allproductos); // Devolver productos en formato JSON
    }

    public function getProductsForAlmacen()
    {
        // Obtener el idalmacen desde la solicitud
        $idalmacen = request()->input('idalmacen');

        // Obtener los productos asociados al idalmacen
        $productos = Producto::whereIn('id', function ($query) use ($idalmacen) {
            $query->select('idproducto')
                ->from('almacen_productos')
                ->where('idalmacen', $idalmacen);
        })->get();

        return response()->json($productos); // Devolver productos en formato JSON
    }


    public function getInfo(Request $request)
    {
        // Obtener los IDs de los productos seleccionados desde la solicitud
        $productosSeleccionados = $request->input('productos', []);

        // Obtener información completa de los productos seleccionados desde la base de datos
        $productos = Producto::whereIn('id', $productosSeleccionados)->get();

        // Devolver los productos como respuesta
        return response()->json($productos);
    }
}
