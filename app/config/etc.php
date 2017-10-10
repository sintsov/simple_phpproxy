<?php
/*
|--------------------------------------------------------------------------
| Configuration file
|--------------------------------------------------------------------------
|
*/

return [
    'config.cache' => [
        'files' => new Illuminate\Filesystem\Filesystem(),
        'config' => [
            'cache.default' => 'files',
            'cache.stores.files' => [
                'driver'  => 'file',
                'path'    => __DIR__ . '/../../cache'
            ]
        ],
    ],
    'container' => new Illuminate\Container\Container(),
    'Cache' => function (DI\Container $c) {
        $container = $c->get('container');
        $container->files = $c->get('config.cache')['files'];
        $container->config = $c->get('config.cache')['config'];
        $cacheManager = new Illuminate\Cache\CacheManager($c->get('container'));
        return $cacheManager->driver();
    },
    'Proxy' => function (DI\Container $c) {
        return new SimplePHPProxy\Proxy(new GuzzleHttp\Client(), $c->get('Cache'));
    }
];
