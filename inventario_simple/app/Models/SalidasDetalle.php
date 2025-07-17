<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SalidasDetalle extends Model
{
    /** @use HasFactory<\Database\Factories\SalidasDetalleFactory> */
    use HasFactory;
    protected $fillable = [
        'salida_id',
        'producto_id',
        'cantidad',
        'precio_unitario',
    ];
    protected $casts = [
        'cantidad' => 'decimal:2',
        'precio_unitario' => 'decimal:2',
    ];
    public function salida()
    {
        return $this->belongsTo(Salidas::class, 'salida_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }

}
