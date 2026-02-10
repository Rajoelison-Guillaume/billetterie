<?php

namespace App\Enums;

/**
 * Rôles disponibles pour les utilisateurs
 * 
 * @package App\Enums
 */
enum UserRole: string
{
    case CLIENT = 'client';
    case ADMIN = 'admin';

    /**
     * Libellé lisible du rôle
     */
    public function label(): string
    {
        return match($this) {
            self::CLIENT => 'Client',
            self::ADMIN  => 'Administrateur',
        };
    }

    /**
     * Vérifier si le rôle est admin
     */
    public function isAdmin(): bool
    {
        return $this === self::ADMIN;
    }

    /**
     * Vérifier si le rôle est client
     */
    public function isClient(): bool
    {
        return $this === self::CLIENT;
    }

    /**
     * Tous les rôles disponibles
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
