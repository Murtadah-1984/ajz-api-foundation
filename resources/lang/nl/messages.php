<?php

return [
    // Authentication
    'login' => [
        'success' => 'Succesvol ingelogd',
        'failed' => 'Ongeldige inloggegevens',
        'throttle' => 'Te veel inlogpogingen. Probeer het opnieuw over :seconds seconden.',
        'not_verified' => 'Uw account is niet geverifieerd.',
        'already_logged_in' => 'U bent al ingelogd.',
    ],
    'logout' => [
        'success' => 'Succesvol uitgelogd',
    ],
    'register' => [
        'success' => 'Registratie succesvol',
        'failed' => 'Registratie mislukt',
        'email_taken' => 'Dit e-mailadres is al in gebruik',
    ],
    'password' => [
        'reset' => [
            'success' => 'Wachtwoord is opnieuw ingesteld',
            'failed' => 'Kan wachtwoord niet opnieuw instellen',
            'token_invalid' => 'Ongeldig wachtwoord reset token',
        ],
        'requirements' => [
            'length' => 'Wachtwoord moet minimaal :length tekens bevatten',
            'special' => 'Wachtwoord moet minimaal één speciaal teken bevatten',
            'number' => 'Wachtwoord moet minimaal één cijfer bevatten',
            'mixed_case' => 'Wachtwoord moet zowel hoofdletters als kleine letters bevatten',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Tweefactorauthenticatie ingeschakeld',
        'disabled' => 'Tweefactorauthenticatie uitgeschakeld',
        'verify' => 'Verifieer uw tweefactorauthenticatiecode',
        'invalid' => 'Ongeldige tweefactorauthenticatiecode',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Succesvol geauthenticeerd met :provider',
        'failed' => 'Authenticatie met :provider mislukt',
        'email_required' => 'E-mailtoegang is vereist voor authenticatie',
        'invalid_state' => 'Ongeldige OAuth-status',
        'already_linked' => 'Account is al gekoppeld aan :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Machtiging succesvol aangemaakt',
        'updated' => 'Machtiging succesvol bijgewerkt',
        'deleted' => 'Machtiging succesvol verwijderd',
        'assigned' => 'Machtiging succesvol toegewezen',
        'revoked' => 'Machtiging succesvol ingetrokken',
    ],

    // Roles
    'roles' => [
        'created' => 'Rol succesvol aangemaakt',
        'updated' => 'Rol succesvol bijgewerkt',
        'deleted' => 'Rol succesvol verwijderd',
        'assigned' => 'Rol succesvol toegewezen',
        'revoked' => 'Rol succesvol ingetrokken',
    ],

    // Users
    'users' => [
        'created' => 'Gebruiker succesvol aangemaakt',
        'updated' => 'Gebruiker succesvol bijgewerkt',
        'deleted' => 'Gebruiker succesvol verwijderd',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Uw IP-adres is geblokkeerd vanwege te veel mislukte pogingen',
        'session_expired' => 'Uw sessie is verlopen',
        'invalid_token' => 'Ongeldig of verlopen token',
        'csrf_token_mismatch' => 'Ongeldig CSRF-token',
    ],
];
