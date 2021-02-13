<?php

return [
    'symbols' => [

        // List of symbols to not try to process as an actual stock,
        // because they are common terms in the community typically not related to
        // a stock.
        'ignored' => [
            // Words.
            'AM',
            'PM',
            'THC',
            'CBD',
            // Finance Terms.
            'IPO',
            'CEO',
            'CFO',
            'CTO',
            'PR',
            'LOW',
            'HIGH',
            'BUY',
            'ROI',
            'REIT',
            // Sub Terms.
            'DD',
            'BUY',
            'VERY',
            'SUB',
            'GO',
            'NOW',
            'ON',
            'YOLO',
            'HOLD',
            'HODL',
            // Orgs.
            'CDC',
            'FBI',
            'IRS',
            'NSA',
            'CIA',
            // Places.
            'CA',
            'UK',
            'US',
            'USA',
            'MN',
        ],
    ],
];
