<?php

namespace App\Models;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

    // List of symbols to not try to process as an actual stock,
    // because they are common terms in the community typically not related to
    // a stock.
    protected $ignoredSymbols = [
        // Words.
        'I',
        'A',
        // Sub Terms.
        'DD',
        'BUY',
        'VERY',
        // Orgs.
        'CDC',
        'US',
    ];

    protected $fillable = [
        'url',
    ];

    protected $casts = [
        'posted_at' => 'datetime',
    ];

    public function stocks()
    {
        return $this->belongsToMany(Stock::class);
    }

    public function getPotentialSymbolsInTitleAttribute()
    {
        return $this->symbolsInString($this->title);
    }

    public function getPotentialSymbolsInContentAttribute()
    {
        return $this->symbolsInString($this->content);
    }

    private function symbolsInString($string)
    {
        preg_match_all('/(^|[\s\$])([A-Z]{1,5})($|[\s,.:!?;\'])/', $string, $matches);
        return array_unique(array_filter($matches[2], function ($symbol) {
            return !in_array($symbol, $this->ignoredSymbols);
        }), SORT_REGULAR);
    }

    public function getPotentialSymbolsAttribute()
    {
        return array_unique(array_merge(
            $this->potentialSymbolsInTitle,
            $this->potentialSymbolsInContent
        ), SORT_REGULAR);
    }

    /**
     * Attempt to add stock references to this post.
     */
    public function updateStocks()
    {
        $ids = [];
        foreach ($this->potentialSymbolsInTitle as $symbol) {
            if ($stock = Stock::fromYahoo($symbol)) {
                $ids[] = $stock->id;
            } else {
                // Log symbol does not exist.
            }
        }

        $this->stocks()->sync($ids);
    }
}
