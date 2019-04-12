<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter\Factory;

use App\IronexSchema\Request\Parameter\ScalarParameter\IntegerParameter;

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