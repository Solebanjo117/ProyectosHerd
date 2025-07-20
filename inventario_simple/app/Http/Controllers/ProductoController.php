<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        if($request->filled('search')) {
            $productos = Producto::where('nombre', 'like', '%' . $request->search . '%')
                ->orWhere('codigo_barras', 'like', '%' . $request->search . '%')
                ->orderBy('activo', 'desc')
                ->paginate(5)
                ->withQueryString();
        } else {
            $productos = Producto::orderBy('activo', 'desc')->paginate(perPage: 10);
        }
        
        return Inertia::render('Productos/Productos', [
        'productos' =>$productos,
    ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $producto = new Producto();
      return Inertia::render('Productos/Productos', [
        'productos'  => Producto::orderBy('activo', 'desc')->paginate(perPage: 10),
        'modo' => "create", //create-edit
        'producto' => $producto  ,

    ]);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
        'nombre' => 'required|string',
        'descripcion' => 'nullable|string',
        'precio' => 'required|numeric',
        'cantidad' => 'required|integer',
        'categoria' => 'nullable|string',
        'codigo_barras' => 'nullable|string',
        'activo' => 'boolean',
        'imagen' => 'nullable|image|max:2048', // 2MB máx
    ]);

    if ($request->hasFile('imagen')) {
        $path = $request->file('imagen')->store('productos', 'public');
        $data['imagen'] = '/storage/' . $path;
    }

    Producto::create($data);

    return redirect()->route('productos.index')->with('success', 'Producto creado con éxito.');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $codigoBarras)
    {
        
        $producto = Producto::where('codigo_barras', $codigoBarras)->first();

    if (!$producto) {
        return response()->json(['nombre' => null], 404);
    }

    return response()->json([
        'nombre' => $producto->nombre,
        'producto_id' => $producto->id,
        'precio'=>$producto->precio,
        'codigo_barras'=>$producto->codigo_barras
    ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
       return Inertia::render('Productos/Productos', [
        'productos'  => Producto::orderBy('activo', 'desc')->paginate(perPage: 10),
        'modo' => "edit", //create-edit
        'producto' =>Producto::find($id),

    ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
  

    }
    public function updatePost(Request $request,string $id){
 $producto = Producto::find($id);
      
$data = $request->all();
  $request->validate([
        'imagen' => 'nullable|image|max:5024', // 5MB máx
    ]);
    // Si se sube una nueva imagen
    if ($request->hasFile('imagen')) {
        // Eliminar la imagen anterior si existe
        if ($producto->imagen) {
            // Extraer solo la ruta relativa (ej: productos/imagen.jpg)
            $rutaAnterior = str_replace('/storage/', '', $producto->imagen);
            Storage::disk('public')->delete($rutaAnterior);
        }

        // Guardar la nueva imagen
        $nuevaRuta = $request->file('imagen')->store('productos', 'public');
        $data['imagen'] = '/storage/' . $nuevaRuta;
    }else {
        // Si no se sube una nueva imagen, mantener la imagen actual
        $data['imagen'] = $producto->imagen;
    }

    $producto->update($data);

    return redirect()->route('productos.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
