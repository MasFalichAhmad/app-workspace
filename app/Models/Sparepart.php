<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Sparepart extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'spareparts';
    protected $fillable = ['kode_part', 'nama_part', 'kategori', 'harga', 'stok'];
    
    public function serviceOrderItems()
    {
        return $this->hasMany(ServiceOrderItem::class, 'sparepart_id');
    }
}
