<?php

namespace App\Http\Controllers;

use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class DashboardController extends Controller
{
    public function index()
    {
        $baseUrl = config('services.internal_api.base_url');
        $stats = ['servis_aktif' => 0, 'total_pelanggan' => 0, 'stok_menipis' => 0, 'total_omzet' => 0];
        $rankings = [];

        try {
            $resStats = Http::get($baseUrl.'/api/stats');
            if ($resStats->successful()) {
                $stats = $resStats->json();
            }

            $resSpk = Http::get($baseUrl.'/api/spk-prioritas');
            if ($resSpk->successful()) {
                $rankings = $resSpk->json()['data'] ?? [];
            }
        } catch (ConnectionException $e) {
            // port 8011 belum nyala, masih default
        }

        return view('dashboard', compact('stats', 'rankings'));
    }
}
