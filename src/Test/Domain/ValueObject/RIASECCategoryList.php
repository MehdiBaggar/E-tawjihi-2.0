<?php

namespace App\Test\Domain\ValueObject;

use App\Test\Domain\ValueObject\Enum\RIASECType;

final class RIASECCategoryList
{
    /** @var RIASECType[] */
    private array $categories;

    private function __construct(array $categories)
    {
        $this->categories = $categories;
    }

    public static function all(): self
    {
        return new self(RIASECType::cases());
    }

    public function getCategories(): array
    {
        return $this->categories;
    }

    public function contains(RIASECType $type): bool
    {
        return in_array($type, $this->categories, true);
    }
}