<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Rule\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\Rule\MaxLengthRule;

class MaxLengthRuleFactory
{
    /**
     * @return MaxLengthRule
     */
    public function create(): MaxLengthRule
    {
        return new MaxLengthRule();
    }
}