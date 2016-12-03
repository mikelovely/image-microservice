<?php

use App\Storage\Exceptions\FileNotFoundException;

$app->get('/{path}', function ($request, $response, $args) {
    try {
        $key = "image:{$args['path']}:{$_SERVER['QUERY_STRING']}";

        $image = $this->cache->remember($key, null, function () use ($request, $args) {
            return $this->image->load($this->storage->get($args['path'])->getContents())->withFilters($request->getParams())->stream();
        });

    } catch (FileNotFoundException $e) {
        return $response->withStatus(404)->write('Not found');
    }

    return $response->withHeader('Content-Type', 'image/png')->write($image);
});
