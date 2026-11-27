<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Http\Requests\StoreServiceOrderRequest;
use App\Models\Customer;
use App\Models\ServiceOrder;
use App\Models\ServiceType;
use App\Models\Sparepart;
use Illuminate\Http\Client\ConnectionException;
use Illuminate\Support\Facades\Http;

class ServiceOrderController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $orders = ServiceOrder::with(['customer', 'user', 'serviceType', 'items.sparepart'])
            ->orderBy('created_at', 'desc')
            ->paginate(10);

        return view('service-orders.index', compact('orders'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $customers = Customer::all();
        $serviceTypes = ServiceType::all();
        $spareparts = Sparepart::where('stok', '>', 0)->get();

        return view('service-orders.create', compact('customers', 'serviceTypes', 'spareparts'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreServiceOrderRequest $request)
    {
        $validated = $request->validated();
        $serviceType = ServiceType::findOrFail($validated['service_type_id']);

        $selectedParts = $validated['sparepart_ids'] ?? [];
        $qtys = $validated['qtys'] ?? [];

        // validasi sparepart stock sebelum save
        $itemsToInsert = [];
        $totalSparepart = 0;

        foreach ($selectedParts as $partId) {
            $qty = max(1, (int) ($qtys[$partId] ?? 1));
            $part = Sparepart::findOrFail($partId);

            if ($part->stok < $qty) {
                return back()->withInput()
                    ->withErrors(['sparepart_ids' => "Stok {$part->nama_part} tidak mencukupi (tersisa {$part->stok})."]);
            }

            $subtotal = $part->harga * $qty;
            $totalSparepart += $subtotal;
            $itemsToInsert[] = [
                'part' => $part,
                'qty' => $qty,
                'harga_satuan' => $part->harga,
                'subtotal' => $subtotal,
            ];
        }
        //save header transaksi
        $biayaJasa = (int) $serviceType->biaya_jasa;
        $order = ServiceOrder::create([
            'kode_servis' => 'SRV-'.date('Ymd').'-'.rand(100, 999),
            'customer_id' => $validated['customer_id'],
            'user_id' => auth()->id(),
            'service_type_id' => $serviceType->id,
            'nama_perangkat' => $validated['nama_perangkat'],
            'keluhan' => $validated['keluhan'],
            'tanggal_masuk' => $validated['tanggal_masuk'],
            'estimasi_selesai' => $validated['estimasi_selesai'],
            'tanggal_selesai' => null,
            'biaya_jasa' => $biayaJasa,
            'total_sparepart' => $totalSparepart,
            'total_biaya' => $biayaJasa + $totalSparepart,
            'status' => 'diterima',
            'urgensi' => (int) $validated['urgensi'],
            'ketersediaan_part' => (int) $validated['ketersediaan_part'],
        ]);

        foreach ($itemsToInsert as $row) {
            $order->items()->create([
                'sparepart_id' => $row['part']->id,
                'qty' => $row['qty'],
                'harga_satuan' => $row['harga_satuan'],
                'subtotal' => $row['subtotal'],
            ]);

            $row['part']->decrement('stok', $row['qty']);
        }
        return redirect()->route('service-orders.index')
                ->with('success', "Transaksi {$order->kode_servis} berhasil dibuat dan stok sparepart diperbarui.");
    }
    

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        $order = ServiceOrder::with(['customer', 'user', 'serviceType', 'items.sparepart'])->findOrFail($id);

        return view('service-orders.show', compact('order'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $order = ServiceOrder::with(['customer', 'serviceType', 'items.sparepart'])->findOrFail($id);

        return view('service-orders.edit', compact('order'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $order = ServiceOrder::findOrFail($id);

        $validated = $request->validate([
            'estimasi_selesai' => 'required|date|after_or_equal:tanggal_masuk',
            'status' => 'required|in:diterima,dikerjakan,selesai,diambil',
            'urgensi' => 'required|integer|in:1,3,5',
            'ketersediaan_part' => 'required|integer|in:1,3,5',
        ]);

        if ($validated['status'] === 'selesai' && ! $order->tanggal_selesai) {
            $validated['tanggal_selesai'] = now()->toDateString();
        }

        $order->update($validated);

        return redirect()->route('service-orders.index')
            ->with('success', "Transaksi {$order->kode_servis} berhasil diperbarui.");
    }

    public function tandaiSelesai(string $id)
    {
        $order = ServiceOrder::findOrFail($id);
        $order->update([
            'status' => 'selesai',
            'tanggal_selesai' => now()->toDateString(),
        ]);

        return redirect()->route('service-orders.index')
            ->with('success', "Servis {$order->kode_servis} ditandai selesai.");
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $order = ServiceOrder::with('items.sparepart')->findOrFail($id);

        // Kembalikan stock sparepart sebelum menghapus transaksi
        foreach ($order->items as $item) {
            if ($item->sparepart) {
                $item->sparepart->increment('stok', $item->qty);
            }
        }

        $order->items()->delete();
        $order->delete();

        return redirect()->route('service-orders.index')
            ->with('success', 'Transaksi dihapus dan stok sparepart telah dikembalikan.');
    }

    public function report(Request $request)
    {
        $body = ['data' => [], 'meta' => null];

        try {
            $response = Http::get(config('services.internal_api.base_url').'/api/service-orders', [
                'page' => $request->query('page', 1),
            ]);
            if ($response->successful()) {
                $body = $response->json();
            }
        } catch (ConnectionException $e) {

        }

        return view('service-orders.report', [
            'orders' => $body['data'] ?? [],
            'meta' => $body['meta'] ?? null,
        ]);
    }
}
