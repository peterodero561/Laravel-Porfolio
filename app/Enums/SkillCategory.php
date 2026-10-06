<?php

namespace App\Enums;

enum SkillCategory: string
{
    case Backend = 'backend';
    case Mobile = 'mobile';
    case Ai = 'ai';
    case Devops = 'devops';
    case Frontend = 'frontend';
    case Database = 'database';
    case Other = 'other';

    public function label(): string
    {
        return match ($this) {
            self::Backend => 'Backend',
            self::Mobile => 'Mobile',
            self::Ai => 'AI & Automation',
            self::Devops => 'DevOps & Infrastructure',
            self::Frontend => 'Frontend',
            self::Database => 'Databases',
            self::Other => 'Other',
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
