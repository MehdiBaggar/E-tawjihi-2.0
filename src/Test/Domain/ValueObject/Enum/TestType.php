<?php

namespace App\Test\Domain\ValueObject\Enum;

enum TestType:string
{
    case PERSONALITY = 'personality';
    case ACADEMIC = 'academic';
    case MIXED = 'mixed';
    case PREFERENCES = 'preferences';
}
