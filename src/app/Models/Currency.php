<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Currency extends Model {

    use HasFactory;

    public $incrementing = false;
    protected $keyType = 'string';
    protected $fillable = [
        'id','name','coin_id','current_price','price_change_percentage_24h','image_url','market_cap','symbol'
    ];

    protected static function booted(){
        static::creating(function($model){
            if(!$model->id) $model->id = Str::uuid()->toString();
        });
    }

    public function watchDogs() {
        return $this->hasMany(WatchDog::class, 'currency_id', 'id');
    }
}
