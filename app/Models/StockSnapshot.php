<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class StockSnapshot extends Model
{
    use HasFactory;

    public $timestamps = false;

    protected $casts = [
        'time' => 'datetime:Y-m-d',
    ];

    public function stock()
    {
        return $this->belongsTo(Stock::class);
    }

    public static function createFromStock(Stock $stock)
    {
        $latest = self::where('stock_id', '=', $stock->id)
            ->orderBy('time', 'desc')
            ->first();
        if ($latest && $latest->time->diffInMinutes(now()) < 15) {
            return null;
        }

        $snapshot = new self();

        $popularity = $stock->posts->reduce(function ($carry, $post) {
            return $post->popularity + $carry;
        });

        $snapshot->popularity = $popularity;
        $snapshot->stock_id = $stock->id;
        $snapshot->time = now();
        $snapshot->save();

        return $snapshot;
    }
}
