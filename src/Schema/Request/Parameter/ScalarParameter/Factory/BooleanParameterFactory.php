<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter\Factory;

use App\IronexSchema\Request\Parameter\ScalarParameter\BooleanParameter;

class BooleanParameterFactory
{
    /**
     * @param string $name
     * @return BooleanParameter
     */
    public function create(string $name): BooleanParameter
    {
        return new BooleanParameter($name);
    }
}