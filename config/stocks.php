<?php

return [
    'symbols' => [

        // Usually these symbols are tangential, so they can be safely ignored
        // if they are mentioned within content.
        'ignored_in_content' => [
            'AMZN',
            'AAPL',
            'MSFT',
            'TSLA',
        ],

        // List of symbols to not try to process as an actual stock,
        // because they are common terms in the community typically not related to
        // a stock.
        'ignored' => [
            // Words.
            'AM',
            'PM',
            'THC',
            'CBD',
            'MD',
            'AND',
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
            'EPS',
            'PDT',
            'RSI',
            'USD',
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
            'KEY',
            'PROS',
            'CONS',
            'PRO',
            'CON',
            'EDIT',
            // Orgs.
            'CDC',
            'FBI',
            'IRS',
            'NSA',
            'CIA',
            'SFT',
            'UBS',
            // Places.
            'CA',
            'UK',
            'US',
            'USA',
            'MN',
            // Common.
            'EV',
            'AI',
            'CSV',
            'API',
        ],
    ],
    'ignored_phrases' => [
        'Regulation SHO',
        'TD Bank',
    ],
];
