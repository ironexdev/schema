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
     * @param object $input
     */
    public function setValues(object $input): void;

    /**
     * @return array
     */
    public function toArray(): array;
}