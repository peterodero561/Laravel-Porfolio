<?php

namespace App\Enums;

enum ProjectCategory: string
{
    case Web = 'web';
    case Mobile = 'mobile';
    case AI = 'ai';
    case IoT = 'iot';
    case Backend = 'backend';
    case Automation = 'automation';

    public function label(): string
    {
        return match ($this) {
            self::Web => 'Web',
            self::Mobile => 'Mobile',
            self::AI => 'AI',
            self::IoT => 'IoT',
            self::Backend => 'Backend',
            self::Automation => 'Automation',
        };
    }

    /** @return array<string, string> */
    public static function options(): array
    {
        return collect(self::cases())
            ->mapWithKeys(fn (self $c) => [$c->value => $c->label()])
            ->all();
    }
}
