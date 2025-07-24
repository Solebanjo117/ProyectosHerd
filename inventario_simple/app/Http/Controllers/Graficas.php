<?php

namespace App\Http\Controllers;

use App\Models\Salidas;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Inertia\Inertia;

class Graficas extends Controller
{
    public function index()
    {
             $inicioMes = Carbon::now()->startOfMonth()->toDateString();
            $finMes = Carbon::now()->endOfMonth()->toDateString();
        return Inertia::render('Dashboard',[
           'datos' => Salidas::selectRaw('DATE(salidas.created_at) as fecha, SUM(detalles.cantidad * detalles.precio_unitario) as total')
    ->join('salidas_detalles as detalles', 'salidas.id', '=', 'detalles.salida_id')
    ->where('salidas.tipo', 'Venta')
    ->whereBetween('salidas.created_at', [$inicioMes, $finMes])
    ->groupBy(DB::raw('DATE(salidas.created_at)'))
    ->orderBy('fecha')
    ->get(),


        ]);
    }
}
