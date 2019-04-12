<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter\Rule\Factory;

use App\IronexSchema\Request\Parameter\ScalarParameter\Rule\MinLengthRule;

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