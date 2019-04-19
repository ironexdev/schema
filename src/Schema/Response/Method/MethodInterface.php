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
     * @return array
     */
    public function toArray(): array;

    public function validate(): void;
}