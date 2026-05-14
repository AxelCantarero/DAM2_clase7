<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Factura extends Model
{
    protected $fillable = [
        'numero_factura',
        'cliente',
        'fecha',
        'total'
    ];

    public function detalles()
    {
        return $this->hasMany(DetalleFactura::class);
    }
}
