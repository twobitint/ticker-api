<?php

namespace App\Console\Commands;

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
        \App\Reddit::updateRising('pennystocks+stocks');
        \App\Models\Post::updateRecent();
        \App\Models\Stock::updateTrending();
    }
}
