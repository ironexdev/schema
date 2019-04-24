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
     * @return object
     */
    public function getDefinition(): object;

    /**
     * @return object
     */
    public function getErrors(): object;

    /**
     * @return bool
     */
    public function isValid(): bool;

    /**
     * @param object $input
     */
    public function setValues(object $input): void;

    /**
     * @param object $input
     */
    public function validateInput(object $input): void;
}