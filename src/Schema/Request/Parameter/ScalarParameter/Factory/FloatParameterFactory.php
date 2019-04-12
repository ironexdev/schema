<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Request\Parameter\ScalarParameter\FloatParameter;

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