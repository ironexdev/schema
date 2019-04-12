<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Rule;

use Ironex\Schema\Request\Enum\ErrorEnum;

class MaxLengthRule implements RuleInterface
{
    /**
     * @var int
     */
    private $constraint;

    /**
     * @var string
     */
    private $name = ErrorEnum::MAX_LENGTH;

    /**
     * @param $value
     * @return bool
     */
    public function test($value): bool
    {
        return strlen($value) <= $this->constraint;
    }

    /**
     * @return int
     */
    public function getConstraint(): int
    {
        return $this->constraint;
    }

    /**
     * @param int $constraint
     */
    public function setConstraint(int $constraint): void
    {
        $this->constraint = $constraint;
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
    }
}