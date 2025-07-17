<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Salidas extends Model
{
    /** @use HasFactory<\Database\Factories\SalidasFactory> */
    use HasFactory;
    protected $fillable = [
        'tipo',
        'cliente_id',
        'referencia',
        'estado',
        'observaciones',
    ];
    protected $casts = [
        'estado' => 'string',
    ];
    public function getUpdatedAtFormattedAttribute()
{
    return $this->updated_at?->format('Y-m-d H:i:s');
}

    public function cliente()
    {
        return $this->belongsTo(Cliente::class, 'cliente_id');
    }
    public function detalles()
    {
        return $this->hasMany(SalidasDetalle::class, 'salida_id') 
            ->with('producto:id,nombre,codigo_barras');
    }
}
