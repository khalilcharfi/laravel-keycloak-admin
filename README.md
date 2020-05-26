# Keycloak Admin API Client for Laravel

# Install

Install the package with composer
```
```

Publish the config file

```

```

# Adding a user

Using representations:

```php

$user = UserRepresentation
    ::username($username)
    ->password($password)
    ->build();
KeycloakAdmin::users()->add($user);
```

Or using the fluent api:

```php
KeycloakAdmin::users()
    ->create()
    ->username($username)
    ->password($password)
    ->email($email)
    ->save();
);

// Using an options array
KeycloakAdmin::users()
    ->create([
        'username' => $username,
        'password' => $password,
        'email' => $email
    ])
    ->save();
);

```

