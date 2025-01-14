<?php

return [
    'email' => [
        'required' => 'E-mailadres is verplicht',
        'email' => 'Voer een geldig e-mailadres in',
        'unique' => 'Dit e-mailadres is al in gebruik',
        'exists' => 'Geen account gevonden met dit e-mailadres',
    ],
    'password' => [
        'required' => 'Wachtwoord is verplicht',
        'min' => 'Wachtwoord moet minimaal :min tekens bevatten',
        'confirmed' => 'Wachtwoordbevestiging komt niet overeen',
        'current' => 'Huidig wachtwoord is onjuist',
    ],
    'name' => [
        'required' => 'Naam is verplicht',
        'min' => 'Naam moet minimaal :min tekens bevatten',
        'max' => 'Naam mag niet meer dan :max tekens bevatten',
    ],
    'role' => [
        'required' => 'Rol is verplicht',
        'exists' => 'Geselecteerde rol bestaat niet',
        'invalid' => 'Ongeldige rol geselecteerd',
    ],
    'permission' => [
        'required' => 'Machtiging is verplicht',
        'exists' => 'Geselecteerde machtiging bestaat niet',
        'invalid' => 'Ongeldige machtiging geselecteerd',
    ],
    'token' => [
        'required' => 'Token is verplicht',
        'invalid' => 'Ongeldig token opgegeven',
    ],
    '2fa_code' => [
        'required' => 'Tweefactorauthenticatiecode is verplicht',
        'digits' => 'Tweefactorauthenticatiecode moet :digits cijfers bevatten',
        'invalid' => 'Ongeldige tweefactorauthenticatiecode',
    ],
    'provider' => [
        'required' => 'OAuth-provider is verplicht',
        'supported' => 'Geselecteerde OAuth-provider wordt niet ondersteund',
    ],
    'phone' => [
        'required' => 'Telefoonnummer is verplicht',
        'unique' => 'Dit telefoonnummer is al geregistreerd',
        'format' => 'Ongeldig telefoonnummerformaat',
    ],
    'username' => [
        'required' => 'Gebruikersnaam is verplicht',
        'unique' => 'Deze gebruikersnaam is al in gebruik',
        'alpha_dash' => 'Gebruikersnaam mag alleen letters, cijfers, streepjes en underscores bevatten',
        'min' => 'Gebruikersnaam moet minimaal :min tekens bevatten',
        'max' => 'Gebruikersnaam mag niet meer dan :max tekens bevatten',
    ],
];
