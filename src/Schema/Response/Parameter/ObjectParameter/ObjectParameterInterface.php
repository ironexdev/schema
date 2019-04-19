<?php

namespace Ironex\Schema\Response\Parameter\ObjectParameter;

interface ObjectParameterInterface
{
    /**
     * @param object $input
     */
    public function setValues(object $input): void;
}