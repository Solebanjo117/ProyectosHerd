<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class EntradasDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\EntradasDetalleFactory> */
    use HasFactory;
    protected $table = 'entradas_detalles';
    protected $fillable = [
        'entrada_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
    ];
    public function entrada()
    {
        return $this->belongsTo(Entradas::class, 'entrada_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
}
