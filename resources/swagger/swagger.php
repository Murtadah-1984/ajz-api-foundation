<?php
// config/swagger.php
return [
    'paths' => [
        'docs_json' => 'api-docs.json',
        'docs_yaml' => 'api-docs.yaml',
        'annotations' => [
            base_path('app/Http/Controllers'),
        ],
    ],
];
