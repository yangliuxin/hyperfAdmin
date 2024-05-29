<?php

declare(strict_types=1);

return [
    'scan' => [
        'paths' => [
            BASE_PATH . '/app',
            BASE_PATH . '/vendor/liuxinyang/hyperf-admin/src',
        ],
        'ignore_annotations' => [
            'mixin',
        ],
    ],
];
