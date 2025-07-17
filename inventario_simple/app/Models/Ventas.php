<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Ventas extends Model
{
    /** @use HasFactory<\Database\Factories\VentasFactory> */
    use HasFactory;
    protected $fillable = [
        'producto_id',
        'cliente_id',
        'total',
    ];
    public function producto()
    {
        return $this->belongsTo(Producto::class);
    }
    public function cliente()
    {
        return $this->belongsTo(Cliente::class);
    }
}
