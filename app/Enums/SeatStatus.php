<?php

namespace App\Enums;

/**
 * Statuts possibles pour un siège
 * 
 * @package App\Enums
 */
enum SeatStatus: string
{
    case AVAILABLE = 'available';     // Disponible
    case LOCKED = 'locked';           // Bloqué temporairement (en cours de réservation)
    case RESERVED = 'reserved';       // Réservé (paiement confirmé)
    case SOLD = 'sold';               // Vendu (billet utilisé)

    /**
     * Libellé lisible du statut
     */
    public function label(): string
    {
        return match($this) {
            self::AVAILABLE => 'Disponible',
            self::LOCKED    => 'Bloqué',
            self::RESERVED  => 'Réservé',
            self::SOLD      => 'Vendu',
        };
    }

    /**
     * Couleur pour affichage graphique
     */
    public function color(): string
    {
        return match($this) {
            self::AVAILABLE => 'success',
            self::LOCKED    => 'warning',
            self::RESERVED  => 'info',
            self::SOLD      => 'danger',
        };
    }

    /**
     * Vérifier si le siège peut être sélectionné
     */
    public function isSelectable(): bool
    {
        return $this === self::AVAILABLE;
    }
}
