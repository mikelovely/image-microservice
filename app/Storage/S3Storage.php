<?php

namespace App\Storage;

use Aws\S3\S3Client;
use Noodlehaus\Config;
use App\Storage\Contracts\StorageInterface;
use Aws\S3\Exception\S3Exception;
use App\Storage\Exceptions\FileNotFoundException;

class S3Storage implements StorageInterface
{
    protected $client;

    protected $config;

    public function __construct(S3Client $client, Config $config)
    {
        $this->client = $client;
        $this->config = $config;
    }

    public function get($pathToFile)
    {
        try {
            return $this->client->getObject([
                'Bucket' => $this->config->get('services.s3.bucket'),
                'Key' => $pathToFile
            ])->get('Body');
        } catch (S3Exception $e) {
            $exception = $this->routeException($e);
            throw $exception;
        }
    }

    protected function routeException(S3Exception $e)
    {
        if ($e->getAwsErrorCode() === 'NoSuchKey') {
            return new FileNotFoundException;
        }

        return $e;
    }
}
