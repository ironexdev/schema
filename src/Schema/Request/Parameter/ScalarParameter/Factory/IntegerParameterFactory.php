<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Request\Parameter\ScalarParameter\IntegerParameter;

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