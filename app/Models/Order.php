<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Order extends Model
{
    use HasFactory,SoftDeletes;

    protected $with = ['sales'];
    protected $fillable = [
        'user_id','total_price', 'invoice_id', 'date'
    ];

    public function getTotalPriceAttribute()
    {
        return $this->sales->sum('total_price');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function sales()
    {
        return $this->hasMany(Sale::class);
    }
}
