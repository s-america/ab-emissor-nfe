<?php

return [
    'session' => [
        'idle_timeout_minutes' => (int) env('SESSION_IDLE_TIMEOUT', 30),
        'absolute_timeout_minutes' => (int) env('SESSION_ABSOLUTE_TIMEOUT', 480),
        'renewal_timeout_minutes' => (int) env('SESSION_RENEWAL_TIMEOUT', 30),
    ],
];
