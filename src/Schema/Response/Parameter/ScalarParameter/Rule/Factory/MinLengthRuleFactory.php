<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Rule\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\Rule\MinLengthRule;

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