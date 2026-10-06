<?php

namespace App\Enums;

enum AvailabilityStatus: string
{
    case Available = 'available';
    case Limited = 'limited';
    case Unavailable = 'unavailable';

    public function label(): string
    {
        return match ($this) {
            self::Available => 'Available for work',
            self::Limited => 'Limited availability',
            self::Unavailable => 'Not available',
        };
    }

    public function color(): string
    {
        return match ($this) {
            self::Available => 'emerald',
            self::Limited => 'cyan',
            self::Unavailable => 'default',
        };
    }
}
