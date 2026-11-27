<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class StatsController extends Controller
{
    public function index()
    {
        return response()->json([
            'servis_aktif' => ServiceOrder::whereIn('status', ['diterima', 'dikerjakan'])->count(),
            'total_pelanggan' => Customer::count(),
            'stok_menipis' => Sparepart::where('stok', '<=', 5)->count(),
            'total_omzet' => (int) ServiceOrder::whereIn('status', ['selesai', 'diambil'])->sum('total_biaya'),
        ]);
    }
}

