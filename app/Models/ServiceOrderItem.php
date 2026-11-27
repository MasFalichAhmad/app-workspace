<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ServiceOrderItem extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'service_order_items';
    protected $fillable = ['service_order_id', 'sparepart_id', 'qty', 'harga_satuan', 'subtotal'];
    public function serviceOrder() { return $this->belongsTo(ServiceOrder::class, 'service_order_id'); }
    public function sparepart() { return $this->belongsTo(Sparepart::class, 'sparepart_id'); }
}
