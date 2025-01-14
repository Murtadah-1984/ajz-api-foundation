<?php

return [
    'email' => [
        'required' => 'L\'indirizzo email è obbligatorio',
        'email' => 'Inserisci un indirizzo email valido',
        'unique' => 'Questo indirizzo email è già in uso',
        'exists' => 'Nessun account trovato con questo indirizzo email',
    ],
    'password' => [
        'required' => 'La password è obbligatoria',
        'min' => 'La password deve contenere almeno :min caratteri',
        'confirmed' => 'La conferma della password non corrisponde',
        'current' => 'La password attuale non è corretta',
    ],
    'name' => [
        'required' => 'Il nome è obbligatorio',
        'min' => 'Il nome deve contenere almeno :min caratteri',
        'max' => 'Il nome non può superare i :max caratteri',
    ],
    'role' => [
        'required' => 'Il ruolo è obbligatorio',
        'exists' => 'Il ruolo selezionato non esiste',
        'invalid' => 'Ruolo selezionato non valido',
    ],
    'permission' => [
        'required' => 'Il permesso è obbligatorio',
        'exists' => 'Il permesso selezionato non esiste',
        'invalid' => 'Permesso selezionato non valido',
    ],
    'token' => [
        'required' => 'Il token è obbligatorio',
        'invalid' => 'Token fornito non valido',
    ],
    '2fa_code' => [
        'required' => 'Il codice di autenticazione a due fattori è obbligatorio',
        'digits' => 'Il codice di autenticazione a due fattori deve contenere :digits cifre',
        'invalid' => 'Codice di autenticazione a due fattori non valido',
    ],
    'provider' => [
        'required' => 'Il provider OAuth è obbligatorio',
        'supported' => 'Il provider OAuth selezionato non è supportato',
    ],
    'phone' => [
        'required' => 'Il numero di telefono è obbligatorio',
        'unique' => 'Questo numero di telefono è già registrato',
        'format' => 'Formato del numero di telefono non valido',
    ],
    'username' => [
        'required' => 'Il nome utente è obbligatorio',
        'unique' => 'Questo nome utente è già in uso',
        'alpha_dash' => 'Il nome utente può contenere solo lettere, numeri, trattini e underscore',
        'min' => 'Il nome utente deve contenere almeno :min caratteri',
        'max' => 'Il nome utente non può superare i :max caratteri',
    ],
];
