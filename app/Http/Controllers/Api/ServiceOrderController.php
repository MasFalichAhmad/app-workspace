<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ServiceOrderController extends Controller
{
    public function index()
    {
        $orders = ServiceOrder::with(['customer', 'user', 'serviceType', 'items.sparepart'])->paginate(10);

        return ServiceOrderResource::collection($orders);
    }

    public function tandaiSelesai(string $id)
    {
        $order = ServiceOrder::with(['customer', 'user', 'serviceType', 'items.sparepart'])->findOrFail($id);
        $order->update(['status' => 'selesai', 'tanggal_selesai' => now()->toDateString()]);

        return new ServiceOrderResource($order);
    }
}
