<?php

namespace Ironex\Schema\Request\Parameter\ArrayParameter;

interface ArrayParameterInterface
{
    /**
     * @param array $input
     */
    public function setValues(array $input): void;
}