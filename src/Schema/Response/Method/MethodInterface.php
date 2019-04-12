<?php

namespace Ironex\Schema\Response\Method;

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
    public function validateInput(object $input): void;
}