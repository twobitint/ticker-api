<?php

namespace App\Models;

use App\Models\Stock;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Post extends Model
{
    use HasFactory;

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

    public function getContentHtmlAttribute()
    {
        // Remove links.
        $html = preg_replace('#<a.*?>.*?</a>#i', '', $this->content);

        // Remove other stuff.
        return strip_tags($html, ['p', 'br']);
    }

    public function getHeroIconNameAttribute()
    {
        foreach (config('categories') as $cat => $subcategories) {
            if (in_array($this->subcategory, $subcategories)) {
                switch ($cat) {
                    case 'analysis':
                        return 'clipboard-list';
                    case 'discussion':
                        return 'chat-alt-2';
                    case 'help':
                        return 'question-mark-circle';
                    case 'bullish':
                        return 'trending-up';
                    case 'bearish':
                        return 'trending-down';
                    case 'catalysts':
                        return 'fire';
                    case 'memes':
                        return 'trash';
                }
            }
        }
        return 'collection';
    }

    public function getPotentialSymbolsInTitleAttribute()
    {
        return $this->symbolsInString($this->title);
    }

    public function getPotentialSymbolsInContentAttribute()
    {
        return $this->symbolsInString(strip_tags($this->content))   ;
    }

    private function symbolsInString($string)
    {
        $found = [];
        // First get all dollarsign-led symbols and assume they are stocks.
        if (preg_match_all('/\$([a-zA-Z]{1,5})/', $string, $matches)) {
            $string = preg_replace('/\$[a-zA-Z]{1,5}/', '', $string);
            $found = $matches[1];
        }
        // Then search for allcaps strings, filter, and merge.
        // Do not allow single-character symbols in this search. It must have
        // the $ in this case.
        preg_match_all('/\b[A-Z]{2,5}\b/', $string, $matches);
        $found = array_merge(array_filter($matches[0], function ($symbol) {
            return !in_array($symbol, config('stocks.symbols.ignored'));
        }), $found);
        // Return only one of earch symbol.
        return array_unique($found, SORT_REGULAR);
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
            if ($stock = Stock::fromYahoo($symbol)) {
                $ids[] = $stock->id;
            } else {
                // Log symbol does not exist.
            }
        }

        $this->stocks()->sync($ids);
    }
}
