<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\ServiceOrder;
use Carbon\Carbon;

class SpkController extends Controller
{
    public function index()
    {
        $orders = ServiceOrder::with(['customer', 'serviceType'])
            ->where('status', 'diterima')
            ->get();

        if ($orders->isEmpty()) {
            return response()->json(['data' => []]);
        }

        // 1. matriks keputusan
        $mapped = $orders->map(function ($order) {
            $sisaHari = max(1, (int) Carbon::now()->startOfDay()->diffInDays(Carbon::parse($order->estimasi_selesai), false));

            return [
                'id' => $order->id,
                'kode_servis' => $order->kode_servis,
                'pelanggan' => $order->customer?->nama ?? '-',
                'nama_perangkat' => $order->nama_perangkat,
                'estimasi_selesai' => $order->estimasi_selesai,
                'c1_urgensi' => (float) ($order->urgensi ?: 1),
                'c2_sisa_hari' => (float) $sisaHari,
                'c3_ketersediaan' => (float) ($order->ketersediaan_part ?: 1),
                'c4_nilai_biaya' => max(1, (float) ($order->total_biaya ?: 1)),
            ];
        });

        // 2.cari nilai max benefit & min cost
        $maxC1 = $mapped->max('c1_urgensi');
        $minC2 = $mapped->min('c2_sisa_hari');
        $maxC3 = $mapped->max('c3_ketersediaan');
        $maxC4 = $mapped->max('c4_nilai_biaya');

        // 3.normalisasi dan perankingan
        $rankings = $mapped->map(function ($row) use ($maxC1, $minC2, $maxC3, $maxC4) {
            $r1 = $row['c1_urgensi'] / $maxC1;
            $r2 = $minC2 / $row['c2_sisa_hari'];
            $r3 = $row['c3_ketersediaan'] / $maxC3;
            $r4 = $row['c4_nilai_biaya'] / $maxC4;

            $skor = round((($r1 * 0.35) + ($r2 * 0.30) + ($r3 * 0.20) + ($r4 * 0.15)) * 100, 2);

            $row['skor_saw'] = $skor;
            $row['rekomendasi'] = $skor >= 75 ? 'Kerjakan Sekarang' : ($skor >= 50 ? 'Antrean Berikutnya' : 'Bisa Menyusul');

            return $row;
        })->sortByDesc('skor_saw')->values();

        return response()->json(['data' => $rankings]);
    }
}
