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
        'font' => '',// 为空为默认字体
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
            'models' => ['path' => 'app/Models', 'generate' => false],
            'repositories' => ['path' => 'app/Repositories', 'generate' => false],
            'enums' => ['path' => 'app/Enums', 'generate' => false],
            'requests' => ['path' => 'app/Http/Requests', 'generate' => false],
        ]
    ],
    "module" => [
        'Tenant'
    ],
    "annotation" => [
        'inject' => [
            'namespaces' => [
                'Module\\Tenant\\Http\\Controllers'
            ],
        ],
        'route' => [
            'enable' => true,
            'middleware' => [
                \Illuminate\Routing\Middleware\SubstituteBindings::class
            ],
            'directories' => [
                app_path('Http/Controllers') => [
                    'not_patterns' => ['*Controller.php']
                ],
                base_path('module/Tenant/app/Http/Controllers/V1') => [
                    'prefix' => 'app/tenant/v1',
                    'middleware' => ['api', 'api.case.converter'],
                    'as' => 'app.tenant.',
                    'namespace' => 'Module\Tenant\Http\Controllers\V1\\'
                ]
            ],
        ],
    ]
];
