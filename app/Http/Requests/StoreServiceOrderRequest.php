<?php

namespace App\Http\Requests;

use Illuminate\Contracts\Validation\ValidationRule;
use Illuminate\Foundation\Http\FormRequest;

class StoreServiceOrderRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'customer_id' => 'required|string',
            'service_type_id' => 'required|string',
            'nama_perangkat' => 'required|string|max:150',
            'keluhan' => 'required|string',
            'tanggal_masuk' => 'required|date',
            'estimasi_selesai' => 'required|date|after_or_equal:tanggal_masuk',
            'urgensi' => 'required|integer|in:1,3,5',
            'ketersediaan_part' => 'required|integer|in:1,3,5',
            'sparepart_ids' => 'nullable|array',
            'sparepart_ids.*' => 'string',
            'qtys' => 'nullable|array',
        ];
    }

    public function messages(): array
    {
        return [
            'customer_id.required' => 'Pelanggan wajib dipilih.',
            'service_type_id.required' => 'Jenis layanan wajib dipilih.',
            'nama_perangkat.required' => 'Nama perangkat wajib diisi.',
            'keluhan.required' => 'Keluhan wajib diisi.',
            'estimasi_selesai.after_or_equal' => 'Estimasi selesai tidak boleh sebelum tanggal masuk.',
        ];
    }
}
