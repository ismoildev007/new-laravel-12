<?php

return [
    'name' => 'FileManager',
    'allow_extensions' => 'jpg,jpeg,png,gif,xlx,xlsx',
    'domain' => env('DOMAIN'),
    'static_url' => env('CDN_STATIC_URL'),
    'thumbs' => [
        'icon' => [
            'w' => 50,
            'h' => 50,
            'q' => 80,
            'slug' => 'icon'
        ],
        'small' => [
            'w' => 320,
            'h' => 240,
            'q' => 70,
            'slug' => 'small'
        ],
        'low' => [
            'w' => 640,
            'h' => 480,
            'q' => 70,
            'slug' => 'low'
        ],
        'normal' => [
            'w' => 1024,
            'h' => 768,
            'q' => 100,
            'slug' => 'normal'
        ]
    ],
    'images_ext' => [
        'jpg',
        'jpeg',
        'png',
        'gif',
        'bmp',
        'webp',
        'svg',
    ]
];
