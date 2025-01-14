<?php

return [
    'unauthorized' => 'Ongeautoriseerde toegang',
    'forbidden' => 'Verboden toegang',
    'not_found' => 'Bron niet gevonden',
    'validation_failed' => 'Validatie mislukt',
    'server_error' => 'Interne serverfout',
    
    'auth' => [
        'invalid_credentials' => 'Ongeldige inloggegevens',
        'token_expired' => 'Authenticatietoken is verlopen',
        'token_invalid' => 'Ongeldig authenticatietoken',
        'user_not_found' => 'Gebruiker niet gevonden',
        'not_verified' => 'E-mail niet geverifieerd',
        'already_verified' => 'E-mail is al geverifieerd',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuth-provider niet gevonden',
        'invalid_state' => 'Ongeldige OAuth-status',
        'callback_error' => 'Fout tijdens OAuth-callback',
        'user_denied' => 'Toegang geweigerd door gebruiker',
    ],

    'permissions' => [
        'not_found' => 'Machtiging niet gevonden',
        'already_exists' => 'Machtiging bestaat al',
        'cannot_delete' => 'Kan deze machtiging niet verwijderen',
        'invalid_format' => 'Ongeldig machtigingsformaat',
    ],

    'roles' => [
        'not_found' => 'Rol niet gevonden',
        'already_exists' => 'Rol bestaat al',
        'cannot_delete' => 'Kan deze rol niet verwijderen',
        'invalid_format' => 'Ongeldig rolformaat',
    ],

    'users' => [
        'not_found' => 'Gebruiker niet gevonden',
        'already_exists' => 'Gebruiker bestaat al',
        'cannot_delete' => 'Kan deze gebruiker niet verwijderen',
        'invalid_format' => 'Ongeldig gebruikersformaat',
    ],

    'security' => [
        'ip_blocked' => 'IP-adres is geblokkeerd',
        'too_many_attempts' => 'Te veel pogingen',
        'invalid_2fa' => 'Ongeldige tweefactorauthenticatiecode',
        'session_expired' => 'Sessie is verlopen',
    ],
];
