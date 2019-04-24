<?php

namespace Ironex\Schema\Response\Parameter\ArrayParameter;

interface ArrayParameterInterface
{
    /**
     * @return object
     */
    public function serialize(): object;
    /**
     * @param array $input
     */
    public function setValues(array $input): void;
}