<?php

return [
    
    'services' => [
        
        's3' => [
            'key' => getenv('S3_KEY'),
            'secret' => getenv('S3_SECRET'),
            'region' => getenv('S3_REGION'),
            'bucket' => getenv('S3_BUCKET'),
        ]

    ]

];
