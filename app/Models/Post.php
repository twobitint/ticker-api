<?php

namespace App\Models;

use App\Reddit;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\DB;

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
            // ->withSum(['posts' => function ($query) {
            //     $query->where('posted_at', '>', now()->subDays(7));
            // }], 'popularity')
            // ->orderBy('posts_sum_popularity', 'desc');
    }

    public function getHotAttribute()
    {
        return $this->popularity > 20;
    }

    public function getContentHtmlAttribute()
    {
        $html = $this->content;


        // Remove links.
        $html = preg_replace('#<a.*?>.*?</a>#i', '', $html);


        // Remove other stuff.
        return strip_tags($html, ['p', 'br', 'ul', 'li', 'h1', 'a']);
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

    /**
     * We're going to do extra aggressive filtering in the content area
     * because it's ripe for symbol overkill.
     */
    public function getPotentialSymbolsInContentAttribute()
    {
        $content = $this->content;

        // Remove references to extremely popular stocks.
        $regex = '/\$?\b(' . implode('|', config('stocks.symbols.ignored_in_content')). ')\b/mi';
        $content = preg_replace($regex, '', $content);

        return $this->symbolsInString(strip_tags($content));
    }

    private function symbolsInString($string)
    {
        $found = [];
        // First get all dollarsign-led symbols and assume they are stocks.
        if (preg_match_all('/\$([a-zA-Z]{1,5})/', $string, $matches)) {
            $string = preg_replace('/\$[a-zA-Z]{1,5}/', '', $string);
            $found = $matches[1];
        }

        // Next remove all allcaps multi-word strings.
        $string = preg_replace('/\b[A-Z]+[ .&][ .&A-Z]+\b/', '', $string);

        // Remove any ignored group words.
        $string = preg_replace('/\b(' . implode('|', config('stocks.ignored_phrases')). ')\b/mi', '', $string);

        // Then search for allcaps strings, filter, and merge.
        // Do not allow single-character symbols in this search. It must have
        // the $ in this case.
        preg_match_all('/\b[A-Z]{2,5}\b/', $string, $matches);
        $found = array_merge(array_filter($matches[0], function ($symbol) {
            return !in_array($symbol, $this->ignoredSymbols());
        }), $found);
        // Return only one of earch symbol.
        return array_unique($found, SORT_REGULAR);
    }

    private function ignoredSymbols()
    {
        return array_merge(
            config('stocks.symbols.ignored'),
            DB::table('failed_symbol_lookups')->pluck('symbol')->toArray()
        );
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
            }
        }

        $this->stocks()->sync($ids);
    }
}
