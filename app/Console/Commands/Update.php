<?php

namespace App\Console\Commands;

use App\Reddit;
use App\Models\Post;
use App\Models\Stock;
use App\Models\StockSnapshot;
use Illuminate\Console\Command;

class Update extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tkkr:update';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Run full background update procedure';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return int
     */
    public function handle()
    {
        Reddit::updateRising('pennystocks+stocks');
        $this->handleTrending();
        $this->handleRecent();

        $this->handleStocks();
    }

    /**
     * This is the main method used to run background updates for
     * post data. Since this is query heavy, and we don't really care that much
     * about what happens here, limit the impact by only updating a subset.
     */
    protected function handleRecent()
    {
        // Some update rules:
        //   - Don't update a post that's over a week old
        //   - Don't update a post that's been updated in the last 15 minutes
        //   - Only update up to 10 posts per call
        //   - Prefer higher scoring posts
        $posts = Post::where('posted_at', '>', now()->subDays(7))
            ->where('updated_at', '<', now()->subMinutes(15))
            ->latest()
            ->limit(100)
            ->get();

        return Reddit::updatePosts($posts);
    }

    protected function handleTrending()
    {
        $trending = Stock::trending();
        foreach ($trending as $stock) {
            Reddit::updatePosts($stock->posts);
        }
    }

    protected function handleStocks()
    {
        Stock::with('posts')->chunk(100, function ($stocks) {
            $snaps = [];
            $now = now();
            foreach ($stocks as $stock) {
                $popularity = $stock->posts->sum('popularity');
                if ($popularity != $stock->popularity) {
                    $stock->popularity = $popularity;
                    $stock->save();
                    $snap = new StockSnapshot();
                    $snap->popularity = $popularity;
                    $snap->stock_id = $stock->id;
                    $snap->time = $now;
                    $snaps[] = $snap->toArray();
                }
            }
            StockSnapshot::insert($snaps);
        });
    }
}
