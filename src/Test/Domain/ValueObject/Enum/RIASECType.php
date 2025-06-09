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
}
