<?php

return [
    'unauthorized' => 'Accesso non autorizzato',
    'forbidden' => 'Accesso vietato',
    'not_found' => 'Risorsa non trovata',
    'validation_failed' => 'Validazione fallita',
    'server_error' => 'Errore interno del server',
    
    'auth' => [
        'invalid_credentials' => 'Credenziali non valide',
        'token_expired' => 'Il token di autenticazione è scaduto',
        'token_invalid' => 'Token di autenticazione non valido',
        'user_not_found' => 'Utente non trovato',
        'not_verified' => 'Email non verificata',
        'already_verified' => 'Email già verificata',
    ],

    'oauth' => [
        'provider_not_found' => 'Provider OAuth non trovato',
        'invalid_state' => 'Stato OAuth non valido',
        'callback_error' => 'Errore durante il callback OAuth',
        'user_denied' => 'Accesso negato dall\'utente',
    ],

    'permissions' => [
        'not_found' => 'Permesso non trovato',
        'already_exists' => 'Il permesso esiste già',
        'cannot_delete' => 'Impossibile eliminare questo permesso',
        'invalid_format' => 'Formato del permesso non valido',
    ],

    'roles' => [
        'not_found' => 'Ruolo non trovato',
        'already_exists' => 'Il ruolo esiste già',
        'cannot_delete' => 'Impossibile eliminare questo ruolo',
        'invalid_format' => 'Formato del ruolo non valido',
    ],

    'users' => [
        'not_found' => 'Utente non trovato',
        'already_exists' => 'L\'utente esiste già',
        'cannot_delete' => 'Impossibile eliminare questo utente',
        'invalid_format' => 'Formato dell\'utente non valido',
    ],

    'security' => [
        'ip_blocked' => 'Indirizzo IP bloccato',
        'too_many_attempts' => 'Troppi tentativi',
        'invalid_2fa' => 'Codice di autenticazione a due fattori non valido',
        'session_expired' => 'La sessione è scaduta',
    ],
];
