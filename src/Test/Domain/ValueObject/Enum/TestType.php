<?php

namespace App\Test\Domain\ValueObject\Enum;

enum TestType: string
{
    case PERSONALITY = 'personality';
    case ACADEMIC = 'academic';
    case PROFILE_SYNTHESIS = 'profile_synthese';
    case PREFERENCES = 'preferences';
}
