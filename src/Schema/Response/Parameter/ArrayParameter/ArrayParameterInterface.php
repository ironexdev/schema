<?php

namespace Ironex\Schema\Response\Parameter\ArrayParameter;

interface ArrayParameterInterface
{
    /**
     * @param array $input
     */
    public function setValues(array $input): void;
}