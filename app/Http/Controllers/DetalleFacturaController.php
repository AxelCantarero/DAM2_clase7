<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Models\DetalleFactura;
use App\Models\Factura;

class DetalleFacturaController extends Controller
{

    public function index()
    {
        return DetalleFactura::with('factura')->get();
    }

    public function store(Request $request)
    {
        $request->validate([

            'factura_id' => 'required|exists:facturas,id',

            'producto' => 'required',

            'cantidad' => 'required|numeric',

            'precio_unitario' => 'required|numeric',

        ]);

        $subtotal = $request->cantidad * $request->precio_unitario;

        $detalle = DetalleFactura::create([

            'factura_id' => $request->factura_id,

            'producto' => $request->producto,

            'cantidad' => $request->cantidad,

            'precio_unitario' => $request->precio_unitario,

            'subtotal' => $subtotal,

        ]);

        return response()->json([
            'mensaje' => 'Detalle creado correctamente',
            'detalle' => $detalle
        ], 201);
    }

    public function detallesPorFactura($id)
    {
        $factura = Factura::with('detalles')->findOrFail($id);

        return response()->json($factura->detalles);
    }
    public function show(string $id)
{
    return DetalleFactura::findOrFail($id);
}

public function update(Request $request, string $id)
{
    $detalle = DetalleFactura::findOrFail($id);

    $subtotal = $request->cantidad * $request->precio_unitario;

    $detalle->update([

        'factura_id' => $request->factura_id,

        'producto' => $request->producto,

        'cantidad' => $request->cantidad,

        'precio_unitario' => $request->precio_unitario,

        'subtotal' => $subtotal

    ]);

    return response()->json([
        'mensaje' => 'Detalle actualizado',
        'detalle' => $detalle
    ]);
}

public function destroy(string $id)
{
    $detalle = DetalleFactura::findOrFail($id);

    $detalle->delete();

    return response()->json([
        'mensaje' => 'Detalle eliminado'
    ]);
}
}