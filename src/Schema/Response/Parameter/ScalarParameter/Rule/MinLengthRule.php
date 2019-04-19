<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Rule;

use Ironex\Schema\Response\Enum\ErrorEnum;

class MinLengthRule implements RuleInterface
{
    /**
     * @var int
     */
    private $constraint;

    /**
     * @var string
     */
    private $name = ErrorEnum::MIN_LENGTH;

    /**
     * @param $value
     * @return bool
     */
    public function test($value): bool
    {
        return strlen($value) >= $this->constraint;
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