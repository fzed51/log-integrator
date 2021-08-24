<?php
declare(strict_types=1);

return [
    'mode' => 'dev', // prod |dev
    'log' => [
        'directory' => 'logs/at',
        'channel' => 'log-integrator',
        'history' => 30 // nb jours
    ]
];
