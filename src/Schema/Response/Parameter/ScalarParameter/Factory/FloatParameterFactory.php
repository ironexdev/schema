<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\FloatParameter;

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