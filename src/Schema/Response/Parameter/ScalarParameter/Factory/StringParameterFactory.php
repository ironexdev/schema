<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\Rule\Factory\MaxLengthRuleFactory;
use Ironex\Schema\Response\Parameter\ScalarParameter\Rule\Factory\MinLengthRuleFactory;
use Ironex\Schema\Response\Parameter\ScalarParameter\StringParameter;
use DI\Annotation\Inject;

class StringParameterFactory
{
    /**
     * @Inject
     * @var MaxLengthRuleFactory
     */
    protected $maxLengthRuleFactory;

    /**
     * @Inject
     * @var MinLengthRuleFactory
     */
    protected $minLengthRuleFactory;

    /**
     * @param string $name
     * @return StringParameter
     */
    public function create(string $name): StringParameter
    {
        $maxLengthRule = $this->maxLengthRuleFactory->create();
        $minLengthRule = $this->minLengthRuleFactory->create();

        return new StringParameter($name, $maxLengthRule, $minLengthRule);
    }
}