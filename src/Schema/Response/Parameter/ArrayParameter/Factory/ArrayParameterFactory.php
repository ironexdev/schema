<?php

namespace Ironex\Schema\Response\Parameter\ArrayParameter\Factory;

use Ironex\Schema\Response\Parameter\ArrayParameter\ArrayParameter;
use Ironex\Schema\Response\Parameter\ParameterInterface;

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