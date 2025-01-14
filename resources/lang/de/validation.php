<?php

return [
    'email' => [
        'required' => 'E-Mail-Adresse ist erforderlich',
        'email' => 'Bitte geben Sie eine gültige E-Mail-Adresse ein',
        'unique' => 'Diese E-Mail-Adresse ist bereits vergeben',
        'exists' => 'Kein Konto mit dieser E-Mail-Adresse gefunden',
    ],
    'password' => [
        'required' => 'Passwort ist erforderlich',
        'min' => 'Das Passwort muss mindestens :min Zeichen lang sein',
        'confirmed' => 'Die Passwortbestätigung stimmt nicht überein',
        'current' => 'Das aktuelle Passwort ist nicht korrekt',
    ],
    'name' => [
        'required' => 'Name ist erforderlich',
        'min' => 'Der Name muss mindestens :min Zeichen lang sein',
        'max' => 'Der Name darf nicht länger als :max Zeichen sein',
    ],
    'role' => [
        'required' => 'Rolle ist erforderlich',
        'exists' => 'Die ausgewählte Rolle existiert nicht',
        'invalid' => 'Ungültige Rolle ausgewählt',
    ],
    'permission' => [
        'required' => 'Berechtigung ist erforderlich',
        'exists' => 'Die ausgewählte Berechtigung existiert nicht',
        'invalid' => 'Ungültige Berechtigung ausgewählt',
    ],
    'token' => [
        'required' => 'Token ist erforderlich',
        'invalid' => 'Ungültiges Token angegeben',
    ],
    '2fa_code' => [
        'required' => 'Zwei-Faktor-Authentifizierungscode ist erforderlich',
        'digits' => 'Der Zwei-Faktor-Authentifizierungscode muss :digits Ziffern lang sein',
        'invalid' => 'Ungültiger Zwei-Faktor-Authentifizierungscode',
    ],
    'provider' => [
        'required' => 'OAuth-Provider ist erforderlich',
        'supported' => 'Der ausgewählte OAuth-Provider wird nicht unterstützt',
    ],
    'phone' => [
        'required' => 'Telefonnummer ist erforderlich',
        'unique' => 'Diese Telefonnummer ist bereits registriert',
        'format' => 'Ungültiges Telefonnummernformat',
    ],
    'username' => [
        'required' => 'Benutzername ist erforderlich',
        'unique' => 'Dieser Benutzername ist bereits vergeben',
        'alpha_dash' => 'Der Benutzername darf nur Buchstaben, Zahlen, Bindestriche und Unterstriche enthalten',
        'min' => 'Der Benutzername muss mindestens :min Zeichen lang sein',
        'max' => 'Der Benutzername darf nicht länger als :max Zeichen sein',
    ],
];
