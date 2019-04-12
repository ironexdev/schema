<?php

namespace Ironex\Schema\Request\Parameter\ArrayParameter\Factory;

use Ironex\Schema\Request\Parameter\ArrayParameter\ArrayParameter;
use Ironex\Schema\Request\Parameter\ParameterInterface;

class ArrayParameterFactory
{
    /**
     * @param string $name
     * @param ParameterInterface $parameter
     * @return ArrayParameter
     */
    public function create(string $name, ParameterInterface $parameter): ArrayParameter
    {
        return new ArrayParameter($name, $parameter);
    }
}