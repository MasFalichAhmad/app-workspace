<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class ServiceType extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'service_types';
    protected $fillable = ['nama_layanan', 'biaya_jasa', 'estimasi_hari', 'deskripsi'];
    
    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'service_type_id');
    }
}
