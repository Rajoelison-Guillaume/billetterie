<?php

namespace App\Enums;

/**
 * Méthodes de paiement disponibles (adaptées à Madagascar)
 * 
 * @package App\Enums
 */
enum PaymentMethod: string
{
    case CASH = 'cash';                   // Espèces (validation par admin)
    case MOBILE_MONEY = 'mobile_money';   // API Efaina (Mvola, Orange Money, etc.)
    case BANK_TRANSFER = 'bank_transfer'; // Virement bancaire (futur)

    /**
     * Libellé lisible de la méthode
     */
    public function label(): string
    {
        return match($this) {
            self::CASH           => 'Espèces',
            self::MOBILE_MONEY   => 'Mobile Money',
            self::BANK_TRANSFER  => 'Virement Bancaire',
        };
    }

    /**
     * Vérifier si la méthode nécessite une validation admin
     */
    public function requiresAdminValidation(): bool
    {
        return $this === self::CASH;
    }

    /**
     * Vérifier si la méthode utilise l'API (async)
     */
    public function isAsync(): bool
    {
        return $this === self::MOBILE_MONEY;
    }

    /**
     * Tous les méthodes disponibles
     */
    public static function all(): array
    {
        return array_column(self::cases(), 'value');
    }
}
