<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class Capture extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'tkkr:capture';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Snapshot all stocks.';

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
        \App\Models\StockSnapshot::capture();
    }
}
