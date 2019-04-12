<?php

namespace App\IronexSchema\Request\Parameter\ObjectParameter;

interface ObjectParameterInterface
{
    /**
     * @param $values
     */
    public function setValues($values): void;
}