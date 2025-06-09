<?php

namespace App\Test\Domain\Entity;

use App\Test\Domain\Entity\AbstractTest;
use App\Test\Domain\ValueObject\Enum\RIASECType;
use App\Test\Domain\ValueObject\Enum\TestType;
use App\Test\Domain\ValueObject\RIASECCategoryList;

class PersonalityTest extends AbstractTest
{
    private RIASECCategoryList $categories;

    /**
     * @var array<int>
     */
    private array $questionIds;

    public function __construct(
        int $id,
        string $title,
        string $description,
        TestType $type,
        bool $isActive,
        int $estimatedDuration,
        int $order,
        array $questionIds = []
    ) {
        parent::__construct($id, $title, $description, $type, $isActive, $estimatedDuration, $order);
        $this->categories = RIASECCategoryList::all();
        $this->questionIds = $questionIds;
    }


    /**
     * @return RIASECType[]
     */
    public function getCategories(): array
    {
        return $this->categories->getCategories();
    }

    public function getQuestionIds(): array
    {
        return $this->questionIds;
    }

}
