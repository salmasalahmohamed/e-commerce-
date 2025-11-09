<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    protected $guarded=[];
    public function user(){
        return $this->belongsTo(User::class);
    }

    public function address(){
        return $this->hasOne(Address::class);
    }
    public function Items(){
        return $this->hasMany(OrderItem::class);
    }
    protected $casts = [
        'grand_total' => 'decimal:2',
    ];
}
