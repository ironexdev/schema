<?php

namespace Ironex\Schema\Request\Parameter\ObjectParameter;

interface ObjectParameterInterface
{
    /**
     * @param $values
     */
    public function setValues($values): void;
}