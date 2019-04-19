<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Response\Parameter\ScalarParameter\BooleanParameter;

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