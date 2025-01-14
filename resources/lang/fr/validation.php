<?php

return [
    'email' => [
        'required' => 'L\'adresse email est obligatoire',
        'email' => 'Veuillez entrer une adresse email valide',
        'unique' => 'Cette adresse email est déjà utilisée',
        'exists' => 'Aucun compte trouvé avec cette adresse email',
    ],
    'password' => [
        'required' => 'Le mot de passe est obligatoire',
        'min' => 'Le mot de passe doit contenir au moins :min caractères',
        'confirmed' => 'La confirmation du mot de passe ne correspond pas',
        'current' => 'Le mot de passe actuel est incorrect',
    ],
    'name' => [
        'required' => 'Le nom est obligatoire',
        'min' => 'Le nom doit contenir au moins :min caractères',
        'max' => 'Le nom ne peut pas dépasser :max caractères',
    ],
    'role' => [
        'required' => 'Le rôle est obligatoire',
        'exists' => 'Le rôle sélectionné n\'existe pas',
        'invalid' => 'Rôle sélectionné invalide',
    ],
    'permission' => [
        'required' => 'La permission est obligatoire',
        'exists' => 'La permission sélectionnée n\'existe pas',
        'invalid' => 'Permission sélectionnée invalide',
    ],
    'token' => [
        'required' => 'Le jeton est obligatoire',
        'invalid' => 'Jeton fourni invalide',
    ],
    '2fa_code' => [
        'required' => 'Le code d\'authentification à deux facteurs est obligatoire',
        'digits' => 'Le code d\'authentification à deux facteurs doit contenir :digits chiffres',
        'invalid' => 'Code d\'authentification à deux facteurs invalide',
    ],
    'provider' => [
        'required' => 'Le fournisseur OAuth est obligatoire',
        'supported' => 'Le fournisseur OAuth sélectionné n\'est pas pris en charge',
    ],
    'phone' => [
        'required' => 'Le numéro de téléphone est obligatoire',
        'unique' => 'Ce numéro de téléphone est déjà enregistré',
        'format' => 'Format de numéro de téléphone invalide',
    ],
    'username' => [
        'required' => 'Le nom d\'utilisateur est obligatoire',
        'unique' => 'Ce nom d\'utilisateur est déjà utilisé',
        'alpha_dash' => 'Le nom d\'utilisateur ne peut contenir que des lettres, des chiffres, des tirets et des underscores',
        'min' => 'Le nom d\'utilisateur doit contenir au moins :min caractères',
        'max' => 'Le nom d\'utilisateur ne peut pas dépasser :max caractères',
    ],
];
