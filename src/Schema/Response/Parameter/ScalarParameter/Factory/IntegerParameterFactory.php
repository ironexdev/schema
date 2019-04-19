<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\IntegerParameter;

class IntegerParameterFactory
{
    /**
     * @param string $name
     * @return IntegerParameter
     */
    public function create(string $name): IntegerParameter
    {
        return new IntegerParameter($name);
    }
}