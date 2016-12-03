<?php

return [
    
    'database' => [
        
        'redis' => [
            'host' => getenv('REDIS_HOST'),
            'port' => getenv('REDIS_PORT'),
            'password' => getenv('REDIS_PASSWORD'),
        ]

    ]

];
