<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Rule\Factory;

use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\MaxLengthRule;

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