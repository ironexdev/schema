<?php

namespace App\IronexSchema\Request\Method;

interface MethodInterface
{
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