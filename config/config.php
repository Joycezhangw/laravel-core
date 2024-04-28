<?php

return [
    'passport' => [
        'check_captcha_cache_key' => 'captcha_uniq_id',
        'password_salt' => env('LANDAO_PASSPORT_PASSWORD_SALT', env('APP_KEY'))
    ],
    'security' => [
        'security_key' => env('LANDAO_CRYPT_SECURITY_KEY', md5('landao_admin')),
        'security_iv' => env('LANDAO_CRYPT_SECURITY_IV', str_repeat("\0", 16))
    ],
    'captcha' => [
        'charset' => 'abcdefghkmnprstuvwxyzABCDEFGHKMNPRSTUVWXYZ23456789',
        'codelen' => 4,
        'width' => 130,
        'height' => 50,
        // 为空为默认字体
        'font' => '',
        'fontsize' => 20,
        'cachetime' => 300,
    ],
    'paginate' => [
        'page_size' => 20
    ],
    'generator' => [
        'basePath' => app()->path(),
        'rootNamespace' => 'App\\',
        'paths' => [
            'models' => 'Services\\Models',
            'repositories' => 'Services\\Repositories',
            'interfaces' => 'Services\\Repositories',
            'enums' => 'Services\\Enums',
        ]
    ],
    "module" => [
    ],
];
