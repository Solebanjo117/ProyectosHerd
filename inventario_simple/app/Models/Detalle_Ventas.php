<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Detalle_Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\DetalleVentasFactory> */
    use HasFactory;
    protected $fillable = [
        'venta_id',
        'producto_id',
        'cantidad',
        'precio',
    ];
    public function venta()
    {
        return $this->belongsTo(Ventas::class, 'venta_id');
    }
    public function producto()
    {
        return $this->belongsTo(Producto::class, 'producto_id');
    }
    public function scopeWithVentaAndProducto($query)
    {
        return $query->with(['venta', 'producto']);
    }
    public function scopeWithVenta($query)
    {
        return $query->with('venta');
    }
}
