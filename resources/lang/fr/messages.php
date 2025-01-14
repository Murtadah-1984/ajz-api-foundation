<?php

return [
    // Authentication
    'login' => [
        'success' => 'Connexion réussie',
        'failed' => 'Identifiants invalides',
        'throttle' => 'Trop de tentatives de connexion. Veuillez réessayer dans :seconds secondes.',
        'not_verified' => 'Votre compte n\'est pas vérifié.',
        'already_logged_in' => 'Vous êtes déjà connecté.',
    ],
    'logout' => [
        'success' => 'Déconnexion réussie',
    ],
    'register' => [
        'success' => 'Inscription réussie',
        'failed' => 'Échec de l\'inscription',
        'email_taken' => 'Cette adresse email est déjà utilisée',
    ],
    'password' => [
        'reset' => [
            'success' => 'Le mot de passe a été réinitialisé',
            'failed' => 'Impossible de réinitialiser le mot de passe',
            'token_invalid' => 'Le jeton de réinitialisation du mot de passe est invalide',
        ],
        'requirements' => [
            'length' => 'Le mot de passe doit contenir au moins :length caractères',
            'special' => 'Le mot de passe doit contenir au moins un caractère spécial',
            'number' => 'Le mot de passe doit contenir au moins un chiffre',
            'mixed_case' => 'Le mot de passe doit contenir des majuscules et des minuscules',
        ],
    ],

    // Two Factor Authentication
    '2fa' => [
        'enabled' => 'Authentification à deux facteurs activée',
        'disabled' => 'Authentification à deux facteurs désactivée',
        'verify' => 'Veuillez vérifier votre code d\'authentification à deux facteurs',
        'invalid' => 'Code d\'authentification à deux facteurs invalide',
    ],

    // OAuth
    'oauth' => [
        'success' => 'Authentification réussie avec :provider',
        'failed' => 'Échec de l\'authentification avec :provider',
        'email_required' => 'L\'accès à l\'email est requis pour l\'authentification',
        'invalid_state' => 'État OAuth invalide',
        'already_linked' => 'Le compte est déjà lié à :provider',
    ],

    // Permissions
    'permissions' => [
        'created' => 'Permission créée avec succès',
        'updated' => 'Permission mise à jour avec succès',
        'deleted' => 'Permission supprimée avec succès',
        'assigned' => 'Permission attribuée avec succès',
        'revoked' => 'Permission révoquée avec succès',
    ],

    // Roles
    'roles' => [
        'created' => 'Rôle créé avec succès',
        'updated' => 'Rôle mis à jour avec succès',
        'deleted' => 'Rôle supprimé avec succès',
        'assigned' => 'Rôle attribué avec succès',
        'revoked' => 'Rôle révoqué avec succès',
    ],

    // Users
    'users' => [
        'created' => 'Utilisateur créé avec succès',
        'updated' => 'Utilisateur mis à jour avec succès',
        'deleted' => 'Utilisateur supprimé avec succès',
    ],

    // Security
    'security' => [
        'ip_blocked' => 'Votre adresse IP a été bloquée en raison de trop nombreuses tentatives échouées',
        'session_expired' => 'Votre session a expiré',
        'invalid_token' => 'Jeton invalide ou expiré',
        'csrf_token_mismatch' => 'Jeton CSRF invalide',
    ],
];
