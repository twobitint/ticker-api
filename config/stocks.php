<?php

return [
    'symbols' => [

        // List of symbols to not try to process as an actual stock,
        // because they are common terms in the community typically not related to
        // a stock.
        'ignored' => [
            // Words.
            'I',
            'A',
            'AM',
            'PM',
            // Finance Terms.
            'IPO',
            'CEO',
            'CFO',
            'CTO',
            'PR',
            'LOW',
            'HIGH',
            'BUY',
            // Sub Terms.
            'DD',
            'BUY',
            'VERY',
            'SUB',
            'GO',
            'NOW',
            'ON',
            // Orgs.
            'CDC',
            'US',
            'USA',
            'FBI',
            'IRS',
            'NSA',
            'CIA',
        ],
    ],
];
