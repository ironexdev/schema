<?php

namespace Ironex\Schema\Request\Method;

use Ironex\Schema\Request\Parameter\ParameterInterface;

interface MethodInterface
{
    /**
     * @param ParameterInterface $parameter
     * @return ParameterInterface
     */
    public function addParameter(ParameterInterface $parameter): ParameterInterface;

    /**
     * @return array
     */
    public function getDefinition(): array;

    /**
     * @return array
     */
    public function getErrors(): array;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @param object $input
     */
    public function setValues(object $input): void;
}