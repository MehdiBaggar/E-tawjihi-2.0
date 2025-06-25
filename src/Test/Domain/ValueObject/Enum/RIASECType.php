<?php

namespace App\Test\Domain\ValueObject\Enum;

enum RIASECType: string
{
    case REALISTIC = 'Realistic';
    case INVESTIGATIVE = 'Investigative';
    case ARTISTIC = 'Artistic';
    case SOCIAL = 'Social';
    case ENTERPRISING = 'Enterprising';
    case CONVENTIONAL = 'Conventional';

    public static function fromCode(string $code): ?self
    {
        return match (strtoupper($code)) {
            'R' => self::REALISTIC,
            'I' => self::INVESTIGATIVE,
            'A' => self::ARTISTIC,
            'S' => self::SOCIAL,
            'E' => self::ENTERPRISING,
            'C' => self::CONVENTIONAL,
            default => null,
        };
    }
}
