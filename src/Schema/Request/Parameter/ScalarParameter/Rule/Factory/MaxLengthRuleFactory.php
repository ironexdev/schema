<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter\Rule\Factory;

use App\IronexSchema\Request\Parameter\ScalarParameter\Rule\MaxLengthRule;

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