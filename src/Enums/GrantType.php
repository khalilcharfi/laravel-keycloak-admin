<?php

namespace Kcharfi\Laravel\Keycloak\Admin\Enums;

use BenSampo\Enum\Enum;

final class GrantType extends Enum
{
    const PASSWORD = 'password';
    const CLIENT_CREDENTIALS = 'client_credentials';
}
