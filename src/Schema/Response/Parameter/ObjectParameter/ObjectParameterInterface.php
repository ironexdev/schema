<?php

namespace Ironex\Schema\Response\Parameter\ObjectParameter;

use Ironex\Schema\Response\Parameter\ParameterInterface;

interface ObjectParameterInterface
{

    /**
     * @param ParameterInterface $parameter
     * @return ParameterInterface
     */
    public function addParameter(ParameterInterface $parameter): ParameterInterface;

    /**
     * @return object
     */
    public function serialize(): object;

    /**
     * @param object $input
     */
    public function setValues(object $input): void;
}