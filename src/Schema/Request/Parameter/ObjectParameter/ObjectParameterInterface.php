<?php

namespace Ironex\Schema\Request\Parameter\ObjectParameter;

interface ObjectParameterInterface
{
    /**
     * @param object $input
     */
    public function setValues(object $input): void;
}