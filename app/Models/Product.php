<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use HasFactory,SoftDeletes;
    protected $fillable = [
        'purchase_id','price',
        'discount','description',
    ];

    public function getDiscountedPriceAttribute()
    {
        return $this->price - ($this->price * $this->discount / 100); // Here just the example, add your logic to calculate the price.
    }

    public function purchase(){
        return $this->belongsTo(Purchase::class);
    }
}
