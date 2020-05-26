# Laravel Keycloak Admin Library
```text
composer require kcharfi/keycloak-admin
```

```text
php artisan vendor:publish  --provider="Kcharfi\Laravel\Keycloak\Admin\KeycloakServiceProvider"
```



Other configurations can be changed to have a new default value, but we recommend to use .env file:
```text
KEYCLOAK_ADMIN_URL=http://keycloack.test/auth
KEYCLOAK_ADMIN_USERNAME=admin
KEYCLOAK_ADMIN_PASSWORD=admin
KEYCLOAK_ADMIN_REALM=master
KEYCLOAK_ADMIN_CLIENT_ID=test
KEYCLOAK_ADMIN_CLIENT_SECRET=XXXXXXX-XXXXX-XXXX-XXXX-XXXXXXXXX
KEYCLOAK_ADMIN_LOG_LEVEL_SUCCESS=INFO
KEYCLOAK_ADMIN_LOG_LEVEL_ERROR=ERROR
```