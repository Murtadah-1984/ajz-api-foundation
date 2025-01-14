<?php

return [
    'unauthorized' => 'Accès non autorisé',
    'forbidden' => 'Accès interdit',
    'not_found' => 'Ressource non trouvée',
    'validation_failed' => 'Échec de la validation',
    'server_error' => 'Erreur interne du serveur',
    
    'auth' => [
        'invalid_credentials' => 'Identifiants invalides',
        'token_expired' => 'Le jeton d\'authentification a expiré',
        'token_invalid' => 'Jeton d\'authentification invalide',
        'user_not_found' => 'Utilisateur non trouvé',
        'not_verified' => 'Email non vérifié',
        'already_verified' => 'Email déjà vérifié',
    ],

    'oauth' => [
        'provider_not_found' => 'Fournisseur OAuth non trouvé',
        'invalid_state' => 'État OAuth invalide',
        'callback_error' => 'Erreur lors du callback OAuth',
        'user_denied' => 'Accès refusé par l\'utilisateur',
    ],

    'permissions' => [
        'not_found' => 'Permission non trouvée',
        'already_exists' => 'La permission existe déjà',
        'cannot_delete' => 'Impossible de supprimer cette permission',
        'invalid_format' => 'Format de permission invalide',
    ],

    'roles' => [
        'not_found' => 'Rôle non trouvé',
        'already_exists' => 'Le rôle existe déjà',
        'cannot_delete' => 'Impossible de supprimer ce rôle',
        'invalid_format' => 'Format de rôle invalide',
    ],

    'users' => [
        'not_found' => 'Utilisateur non trouvé',
        'already_exists' => 'L\'utilisateur existe déjà',
        'cannot_delete' => 'Impossible de supprimer cet utilisateur',
        'invalid_format' => 'Format d\'utilisateur invalide',
    ],

    'security' => [
        'ip_blocked' => 'Adresse IP bloquée',
        'too_many_attempts' => 'Trop de tentatives',
        'invalid_2fa' => 'Code d\'authentification à deux facteurs invalide',
        'session_expired' => 'La session a expiré',
    ],
];
