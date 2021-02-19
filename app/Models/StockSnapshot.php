<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSnapshot extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'time' => 'datetime:Y-m-d H:i:s',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
