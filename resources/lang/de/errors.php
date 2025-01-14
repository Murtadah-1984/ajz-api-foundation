<?php

return [
    'unauthorized' => 'Nicht autorisierter Zugriff',
    'forbidden' => 'Zugriff verweigert',
    'not_found' => 'Ressource nicht gefunden',
    'validation_failed' => 'Validierung fehlgeschlagen',
    'server_error' => 'Interner Serverfehler',
    
    'auth' => [
        'invalid_credentials' => 'Ungültige Anmeldedaten',
        'token_expired' => 'Authentifizierungstoken ist abgelaufen',
        'token_invalid' => 'Ungültiges Authentifizierungstoken',
        'user_not_found' => 'Benutzer nicht gefunden',
        'not_verified' => 'E-Mail nicht verifiziert',
        'already_verified' => 'E-Mail bereits verifiziert',
    ],

    'oauth' => [
        'provider_not_found' => 'OAuth-Provider nicht gefunden',
        'invalid_state' => 'Ungültiger OAuth-Status',
        'callback_error' => 'Fehler während OAuth-Callback',
        'user_denied' => 'Zugriff vom Benutzer verweigert',
    ],

    'permissions' => [
        'not_found' => 'Berechtigung nicht gefunden',
        'already_exists' => 'Berechtigung existiert bereits',
        'cannot_delete' => 'Diese Berechtigung kann nicht gelöscht werden',
        'invalid_format' => 'Ungültiges Berechtigungsformat',
    ],

    'roles' => [
        'not_found' => 'Rolle nicht gefunden',
        'already_exists' => 'Rolle existiert bereits',
        'cannot_delete' => 'Diese Rolle kann nicht gelöscht werden',
        'invalid_format' => 'Ungültiges Rollenformat',
    ],

    'users' => [
        'not_found' => 'Benutzer nicht gefunden',
        'already_exists' => 'Benutzer existiert bereits',
        'cannot_delete' => 'Dieser Benutzer kann nicht gelöscht werden',
        'invalid_format' => 'Ungültiges Benutzerformat',
    ],

    'security' => [
        'ip_blocked' => 'IP-Adresse wurde gesperrt',
        'too_many_attempts' => 'Zu viele Versuche',
        'invalid_2fa' => 'Ungültiger Zwei-Faktor-Authentifizierungscode',
        'session_expired' => 'Sitzung ist abgelaufen',
    ],
];
