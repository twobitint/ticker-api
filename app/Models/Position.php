<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Position extends Pivot
{
    use HasFactory;

    protected $table = 'positions';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
