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
        'detalles' => 'required|array'
    ]);

    $factura = Factura::create([
        'numero_factura' => $request->numero_factura,
        'cliente' => $request->cliente,
        'fecha' => $request->fecha,
        'total' => 0
    ]);

    $total = 0;

    foreach ($request->detalles as $detalle) {

        $subtotal = $detalle['cantidad'] * $detalle['precio'];

        $factura->detalles()->create([
            'producto' => $detalle['producto'],
            'cantidad' => $detalle['cantidad'],
            'precio' => $detalle['precio'],
            'subtotal' => $subtotal
        ]);

        $total += $subtotal;
    }

    $factura->update([
        'total' => $total
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