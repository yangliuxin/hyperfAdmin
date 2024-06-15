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
    //后台统计抬头
    'statistics' => [
        'show' => true,
        'data' => [
            [
                'icon' => 'ion-ios-gear-outline',
                'class' => 'bg-aqua',
                'title' => '占位符1',
                'data' => 0
            ],
            [
                'icon' => 'ion-android-mail',
                'class' => 'bg-red',
                'title' => '占位符2',
                'data' => 0
            ],
            [
                'icon' => 'ion-ios-cart-outline',
                'class' => 'bg-green',
                'title' => '占位符3',
                'data' => 0
            ],
            [
                'icon' => 'ion-ios-people-outline',
                'class' => 'bg-yellow',
                'title' => '占位符4',
                'data' => 0
            ]
        ],
    ]
];
