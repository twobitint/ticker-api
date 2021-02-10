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
        // Pronouns.
        'I',
        // Sub Terms.
        'DD',
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
        preg_match_all('/(^|[\s\$])([A-Z]{1,5})($|[\s,.:!?;\'])/', $this->title, $matches);
        return array_filter($matches[2], function ($symbol) {
            return !in_array($symbol, $this->ignoredSymbols);
        });
    }

    public function getPotentialSymbolsInContentAttribute()
    {
        preg_match_all('/(^|[\s\$])([A-Z]{1,5})($|[\s,.:!?;\'])/', $this->content, $matches);
        return array_filter($matches[2], function ($symbol) {
            return !in_array($symbol, $this->ignoredSymbols);
        });
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
        foreach ($this->potentialSymbols as $symbol) {
            $stock = Stock::firstOrNew(['symbol' => $symbol]);
            $stock->updateFromYahoo();
            $ids[] = $stock->id;
        }

        $this->stocks()->sync($ids);
    }
}
