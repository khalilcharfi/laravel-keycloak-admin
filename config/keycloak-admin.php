<?php
return [
    'default' => env('KEYCLOAK_ADMIN_DEFAULT_REALM', 'default'),

    'connections' => [
        'default' => [
            'url' => env('KEYCLOAK_ADMIN_URL'),
            'username' => env('KEYCLOAK_ADMIN_USERNAME'),
            'password' => env('KEYCLOAK_ADMIN_PASSWORD'),
            'realm' => env('KEYCLOAK_ADMIN_REALM'),
            'client-id' => env('KEYCLOAK_ADMIN_CLIENT_ID'),
            'client-secret' => env('KEYCLOAK_ADMIN_CLIENT_SECRET'),
            'logging' => [
                'success-level' => env('KEYCLOAK_ADMIN_LOG_LEVEL_SUCCESS', 'INFO'),
                'failure-level' => env('KEYCLOAK_ADMIN_LOG_LEVEL_ERROR', 'ERROR'),
                'channel' => env('KEYCLOAK_ADMIN_LOG_CHANNEL')
            ]
        ]
    ]
];
