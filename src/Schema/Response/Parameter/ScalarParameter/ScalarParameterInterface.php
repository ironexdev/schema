<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter;

interface ScalarParameterInterface
{
    /**
     * @param mixed $input
     */
    public function setValue($input): void;
}