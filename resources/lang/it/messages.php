<?php

return [
    // Authentication
    'login' => [
        'success' => 'Accesso effettuato con successo',
        'failed' => 'Credenziali non valide',
        'throttle' => 'Troppi tentativi di accesso. Riprova tra :seconds secondi.',
        'not_verified' => 'Il tuo account non è verificato.',
        'already_logged_in' => 'Hai già effettuato l\'accesso.',
    ],
    'logout' => [
        'success' => 'Disconnessione effettuata con successo',
    ],
    'register' => [
        'success' => 'Registrazione completata con successo',
        'failed' => 'Registrazione fallita',
        'email_taken' => 'Questa email è già in uso',
    ],
    'password' => [
        'reset' => [
            'success' => 'La password è stata reimpostata',
            'failed' => 'Impossibile reimpostare la password',
            'token_invalid' => 'Il token di reimpostazione password non è valido',
        ],
        'requirements' => [
            'length' => 'La password deve contenere almeno :length caratteri',
            'special' => 'La password deve contenere almeno un carattere speciale',
            'number' => 'La password deve contenere almeno un numero',
            'mixed_case' => 'La password deve contenere lettere maiuscole e minuscole',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Autenticazione a due fattori abilitata',
        'disabled' => 'Autenticazione a due fattori disabilitata',
        'verify' => 'Verifica il tuo codice di autenticazione a due fattori',
        'invalid' => 'Codice di autenticazione a due fattori non valido',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Autenticazione riuscita con :provider',
        'failed' => 'Autenticazione fallita con :provider',
        'email_required' => 'L\'accesso all\'email è richiesto per l\'autenticazione',
        'invalid_state' => 'Stato OAuth non valido',
        'already_linked' => 'Account già collegato a :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permesso creato con successo',
        'updated' => 'Permesso aggiornato con successo',
        'deleted' => 'Permesso eliminato con successo',
        'assigned' => 'Permesso assegnato con successo',
        'revoked' => 'Permesso revocato con successo',
    ],

    // Roles
    'roles' => [
        'created' => 'Ruolo creato con successo',
        'updated' => 'Ruolo aggiornato con successo',
        'deleted' => 'Ruolo eliminato con successo',
        'assigned' => 'Ruolo assegnato con successo',
        'revoked' => 'Ruolo revocato con successo',
    ],

    // Users
    'users' => [
        'created' => 'Utente creato con successo',
        'updated' => 'Utente aggiornato con successo',
        'deleted' => 'Utente eliminato con successo',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Il tuo indirizzo IP è stato bloccato a causa di troppi tentativi falliti',
        'session_expired' => 'La tua sessione è scaduta',
        'invalid_token' => 'Token non valido o scaduto',
        'csrf_token_mismatch' => 'Token CSRF non valido',
    ],
];
