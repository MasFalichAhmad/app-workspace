<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ServiceOrder extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'service_orders';

    protected $fillable = [
    'kode_servis', 'customer_id', 'user_id', 'service_type_id',
    'nama_perangkat', 'keluhan', 'tanggal_masuk', 'estimasi_selesai',
    'tanggal_selesai', 'biaya_jasa', 'total_sparepart', 'total_biaya',
    'status', 'urgensi', 'ketersediaan_part',
    ];
    public function customer() { return $this->belongsTo(Customer::class, 'customer_id'); }
    public function user() { return $this->belongsTo(User::class, 'user_id'); }
    public function serviceType() { return $this->belongsTo(ServiceType::class, 'service_type_id'); }
    public function items() { return $this->hasMany(ServiceOrderItem::class, 'service_order_id'); }
}
