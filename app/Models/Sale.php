<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Sale extends Model
{
    use HasFactory,SoftDeletes;

    protected $fillable = [
        'order_id', 'user_id', 'product_id','quantity','total_price'
    ];

    public function user(){
        return $this->belongsTo(User::class);
    }

    public function product(){
        return $this->belongsTo(Product::class);
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }

    public function order()
    {
        return $this->belongsTo(Order::class);
    }
}