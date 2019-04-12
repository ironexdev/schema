<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

interface ScalarParameterInterface
{
    /**
     * @param mixed $value
     * @return $this
     */
    public function setValue($value);
}