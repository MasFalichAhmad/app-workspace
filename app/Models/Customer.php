<?php

namespace App\Models;

use MongoDB\Laravel\Eloquent\Model;

class Customer extends Model
{
    protected $connection = 'mongodb';
    protected $collection = 'customers';

    protected $fillable = ['nama', 'no_hp', 'email', 'alamat'];

    public function serviceOrders()
    {
        return $this->hasMany(ServiceOrder::class, 'customer_id');
    }
}