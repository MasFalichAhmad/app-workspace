<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class ServiceOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'kode_servis' => $this->kode_servis,
            'customer' => ['id' => $this->customer?->id, 'nama' => $this->customer?->nama],
            'teknisi' => ['id' => $this->user?->id, 'name' => $this->user?->name],
            'layanan' => $this->serviceType?->nama_layanan,
            'nama_perangkat' => $this->nama_perangkat,
            'tanggal_masuk' => $this->tanggal_masuk,
            'estimasi_selesai' => $this->estimasi_selesai,
            'total_biaya' => $this->total_biaya,
            'status' => $this->status,
            'spareparts' => $this->items->map(fn ($item) => [
                'nama_part' => $item->sparepart?->nama_part,
                'qty' => $item->qty,
                'subtotal' => $item->subtotal,
            ]),
        ];
    }
}
