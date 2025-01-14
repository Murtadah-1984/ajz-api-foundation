<?php

return [
    // Authentication
    'login' => [
        'success' => 'Erfolgreich angemeldet',
        'failed' => 'Ungültige Anmeldedaten',
        'throttle' => 'Zu viele Anmeldeversuche. Bitte versuchen Sie es in :seconds Sekunden erneut.',
        'not_verified' => 'Ihr Konto ist nicht verifiziert.',
        'already_logged_in' => 'Sie sind bereits angemeldet.',
    ],
    'logout' => [
        'success' => 'Erfolgreich abgemeldet',
    ],
    'register' => [
        'success' => 'Registrierung erfolgreich',
        'failed' => 'Registrierung fehlgeschlagen',
        'email_taken' => 'Diese E-Mail-Adresse ist bereits vergeben',
    ],
    'password' => [
        'reset' => [
            'success' => 'Passwort wurde zurückgesetzt',
            'failed' => 'Passwort konnte nicht zurückgesetzt werden',
            'token_invalid' => 'Token für Passwort-Zurücksetzung ist ungültig',
        ],
        'requirements' => [
            'length' => 'Das Passwort muss mindestens :length Zeichen lang sein',
            'special' => 'Das Passwort muss mindestens ein Sonderzeichen enthalten',
            'number' => 'Das Passwort muss mindestens eine Zahl enthalten',
            'mixed_case' => 'Das Passwort muss Groß- und Kleinbuchstaben enthalten',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Zwei-Faktor-Authentifizierung aktiviert',
        'disabled' => 'Zwei-Faktor-Authentifizierung deaktiviert',
        'verify' => 'Bitte verifizieren Sie Ihren Zwei-Faktor-Authentifizierungscode',
        'invalid' => 'Ungültiger Zwei-Faktor-Authentifizierungscode',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Erfolgreich authentifiziert mit :provider',
        'failed' => 'Authentifizierung mit :provider fehlgeschlagen',
        'email_required' => 'E-Mail-Zugriff ist für die Authentifizierung erforderlich',
        'invalid_state' => 'Ungültiger OAuth-Status',
        'already_linked' => 'Konto ist bereits mit :provider verknüpft',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Berechtigung erfolgreich erstellt',
        'updated' => 'Berechtigung erfolgreich aktualisiert',
        'deleted' => 'Berechtigung erfolgreich gelöscht',
        'assigned' => 'Berechtigung erfolgreich zugewiesen',
        'revoked' => 'Berechtigung erfolgreich entzogen',
    ],

    // Roles
    'roles' => [
        'created' => 'Rolle erfolgreich erstellt',
        'updated' => 'Rolle erfolgreich aktualisiert',
        'deleted' => 'Rolle erfolgreich gelöscht',
        'assigned' => 'Rolle erfolgreich zugewiesen',
        'revoked' => 'Rolle erfolgreich entzogen',
    ],

    // Users
    'users' => [
        'created' => 'Benutzer erfolgreich erstellt',
        'updated' => 'Benutzer erfolgreich aktualisiert',
        'deleted' => 'Benutzer erfolgreich gelöscht',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Ihre IP-Adresse wurde aufgrund zu vieler fehlgeschlagener Versuche gesperrt',
        'session_expired' => 'Ihre Sitzung ist abgelaufen',
        'invalid_token' => 'Ungültiges oder abgelaufenes Token',
        'csrf_token_mismatch' => 'Ungültiges CSRF-Token',
    ],
];
