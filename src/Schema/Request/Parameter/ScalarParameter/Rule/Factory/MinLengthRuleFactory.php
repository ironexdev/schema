<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Rule\Factory;

use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\MinLengthRule;

class MinLengthRuleFactory
{
    /**
     * @return MinLengthRule
     */
    public function create(): MinLengthRule
    {
        return new MinLengthRule();
    }
}