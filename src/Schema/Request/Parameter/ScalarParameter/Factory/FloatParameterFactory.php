<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter\Factory;

use App\IronexSchema\Request\Parameter\ScalarParameter\FloatParameter;

class FloatParameterFactory
{
    /**
     * @param string $name
     * @return FloatParameter
     */
    public function create(string $name): FloatParameter
    {
        return new FloatParameter($name);
    }
}