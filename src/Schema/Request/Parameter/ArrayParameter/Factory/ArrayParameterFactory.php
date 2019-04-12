<?php

namespace App\IronexSchema\Request\Parameter\ArrayParameter\Factory;

use App\IronexSchema\Request\Parameter\ArrayParameter\ArrayParameter;
use App\IronexSchema\Request\Parameter\ParameterInterface;

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