<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter\Factory;

use Ironex\Schema\Request\Parameter\ScalarParameter\BooleanParameter;

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