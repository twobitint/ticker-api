<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Relations\Pivot;

class Watcher extends Pivot
{
    use HasFactory;

    protected $table = 'watchlist';

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }
}
