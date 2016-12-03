<?php

use Slim\App;
use Noodlehaus\Config;
use Dotenv\Dotenv;
use App\Storage\S3Storage;
use Aws\S3\S3Client;
use App\Image\Manipulator;
use Intervention\Image\ImageManager;
use App\Cache\RedisCache;
use Predis\Client as Predis;

require __DIR__ . '/../vendor/autoload.php';

$app = new App([
    'settings' => [
        'displayErrorDetails' => false,
    ]
]);

if (file_exists(__DIR__ . '/../.env')) {
    $dotenv = new Dotenv(__DIR__ . '/../');
    $dotenv->load();
}

$container = $app->getContainer();

$container['config'] = function ($c) {
    return new Config(__DIR__ . '/../config');
};

$container['storage'] = function ($c) {
    $client = new S3Client([
        'version' => 'latest',
        'region' => $c->config->get('services.s3.region'),
        'credentials' => [
            'key' => $c->config->get('services.s3.key'),
            'secret' => $c->config->get('services.s3.secret'),
        ]
    ]);

    return new S3Storage($client, $c->config);
};

$container['image'] = function ($c) {
    return new Manipulator(new ImageManager, $c->config);
};

$container['cache'] = function ($c) {
    $client = new Predis([
        'scheme' => 'tcp',
        'host' => $c->config->get('database.redis.host'),
        'port' => $c->config->get('database.redis.port'),
        'password' => $c->config->get('database.redis.password') ?: null,
    ]);

    return new RedisCache($client);
};

require __DIR__ . '/../routes/web.php';
