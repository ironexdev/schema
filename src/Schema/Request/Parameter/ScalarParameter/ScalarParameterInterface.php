<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

interface ScalarParameterInterface
{
    /**
     * @param mixed $input
     */
    public function setValue($input): void;
}