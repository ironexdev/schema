<?php

namespace Ironex\Schema\Request\Parameter\ObjectParameter;

use Ironex\Schema\Request\Parameter\ParameterInterface;

interface ObjectParameterInterface
{
    /**
     * @param ParameterInterface $parameter
     * @return ParameterInterface
     */
    public function addParameter(ParameterInterface $parameter): ParameterInterface;

    /**
     * @param object $input
     */
    public function setValues(object $input): void;
}