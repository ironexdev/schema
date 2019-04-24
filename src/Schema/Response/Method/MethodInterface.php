<?php

namespace Ironex\Schema\Response\Method;

use Ironex\Schema\Response\Parameter\ParameterInterface;

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
     * @return object
     */
    public function serialize(): object;

    public function validate(): void;
}