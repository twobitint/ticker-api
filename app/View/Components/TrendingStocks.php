<?php

namespace App\View\Components;

use App\Models\Stock;
use Illuminate\View\Component;

class TrendingStocks extends Component
{
    public $stocks;

    /**
     * Create a new component instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->stocks = Stock::trending();
    }

    /**
     * Get the view / contents that represent the component.
     *
     * @return \Illuminate\Contracts\View\View|string
     */
    public function render()
    {
        return view('components.trending-stocks');
    }
}
