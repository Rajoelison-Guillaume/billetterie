<?php

namespace App\Enums;

/**
 * Statuts possibles pour un paiement
 * 
 * Workflow : PENDING → SUCCESS
 *           PENDING → FAILED
 * 
 * @package App\Enums
 */
enum PaymentStatus: string
{
    case PENDING = 'pending';       // En attente (initié)
    case SUCCESS = 'success';       // Réussi
    case FAILED = 'failed';         // Échoué
    case EXPIRED = 'expired';       // Délai de paiement dépassé

    /**
     * Libellé lisible du statut
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING  => 'En attente',
            self::SUCCESS  => 'Réussi',
            self::FAILED   => 'Échoué',
            self::EXPIRED  => 'Expiré',
        };
    }

    /**
     * Couleur Bootstrap pour badge
     */
    public function badge(): string
    {
        return match($this) {
            self::PENDING  => 'warning',
            self::SUCCESS  => 'success',
            self::FAILED   => 'danger',
            self::EXPIRED  => 'danger',
        };
    }

    /**
     * Vérifier si le paiement est finalisé
     */
    public function isFinalized(): bool
    {
        return in_array($this, [self::SUCCESS, self::FAILED, self::EXPIRED]);
    }
}
