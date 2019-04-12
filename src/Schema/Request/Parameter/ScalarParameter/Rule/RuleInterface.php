<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Rule;

interface RuleInterface
{
    /**
     * @return string|int
     */
    public function getConstraint();

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @param $value
     * @return bool
     */
    public function test($value): bool;
}