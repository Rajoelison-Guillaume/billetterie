<?php

namespace App\Enums;

/**
 * Statuts possibles pour une réservation
 * 
 * Workflow : PENDING → CONFIRMED → EXPIRED
 *           PENDING → CANCELLED
 * 
 * @package App\Enums
 */
enum ReservationStatus: string
{
    case PENDING = 'pending';           // En attente de paiement
    case CONFIRMED = 'confirmed';       // Paiement confirmé
    case EXPIRED = 'expired';           // Dépassement du délai de paiement
    case CANCELLED = 'cancelled';       // Annulée par l'utilisateur ou admin

    /**
     * Libellé lisible du statut
     */
    public function label(): string
    {
        return match($this) {
            self::PENDING    => 'En attente',
            self::CONFIRMED  => 'Confirmée',
            self::EXPIRED    => 'Expirée',
            self::CANCELLED  => 'Annulée',
        };
    }

    /**
     * Couleur Bootstrap pour badge
     */
    public function badge(): string
    {
        return match($this) {
            self::PENDING    => 'warning',
            self::CONFIRMED  => 'success',
            self::EXPIRED    => 'danger',
            self::CANCELLED  => 'secondary',
        };
    }

    /**
     * Vérifier si la réservation est active
     */
    public function isActive(): bool
    {
        return in_array($this, [self::PENDING, self::CONFIRMED]);
    }
}
