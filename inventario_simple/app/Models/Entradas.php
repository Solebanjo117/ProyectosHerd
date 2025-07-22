<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Entradas extends Model
{
    /** @use HasFactory<\Database\Factories\EntradasFactory> */
    use HasFactory;
    protected $table = 'entradas';
    protected $fillable = [
        'referencia',
        'tipo',
        'observaciones',
    ];
    public function detalles()
    {
        return $this->hasMany(EntradasDetalle::class, 'entrada_id');
    }
    
    public function getUpdatedAtFormattedAttribute()
    {
        return $this->updated_at->format('d/m/Y H:i');
    }
}
