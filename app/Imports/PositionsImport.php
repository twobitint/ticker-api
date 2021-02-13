<?php

namespace App\Imports;

use App\Models\Stock;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class PositionsImport implements ToCollection
{
    public function collection(Collection $rows)
    {
        $ids = [];
        foreach ($rows as $row) {
            if ($stock = Stock::fromYahoo($row['symbol'])) {
                $ids[] = $stock->id;
            }
        }

        Auth::user()->stocks()->sync($ids);
    }
}
