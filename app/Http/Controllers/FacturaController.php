<?php

namespace App\Http\Controllers;

use App\Models\Factura;
use Illuminate\Http\Request;

class FacturaController extends Controller
{
  
    public function index()
    {
        return Factura::with('detalles')->get();    
    }

  
    public function store(Request $request)
{
    $request->validate([
        'numero_factura' => 'required',
        'cliente' => 'required',
        'fecha' => 'required|date',
        'total' => 'required',
    ]);

    $factura = Factura::create([
        'numero_factura' => $request->numero_factura,
        'cliente' => $request->cliente,
        'fecha' => $request->fecha,
        'total' => $request->total
    ]);
    
    return response()->json([
        'mensaje' => 'Factura creada correctamente',
        'factura' => $factura->load('detalles')
    ], 201);
}


    public function show(string $id)
    {
        return Factura::findOrFail($id);
    }

    public function update(Request $request, string $id)
    {
        $factura = Factura::findOrFail($id);

        $factura->update([
            'numero_factura' => $request->numero_factura,
            'cliente' => $request->cliente,
            'fecha' => $request->fecha,
            'total' => $request->total
        ]);

        return response()->json([
            'mensaje' => 'Factura actualizada',
            'factura' => $factura
        ]);
    }


    public function destroy(string $id)
    {
        $factura = Factura::findOrFail($id);

        $factura->delete();

        return response()->json([
            'mensaje' => 'Factura eliminada'
        ]);
    }
}