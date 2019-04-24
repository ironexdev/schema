<?php

namespace Ironex\Schema\Response\Parameter;

interface ParameterInterface
{
    /**
     * @param string $error
     * @param bool $constraint
     */
    public function addError(string $error, $constraint = true): void;

    /**
     * @return object
     */
    public function getDefinition(): object;

    /**
     * @return object
     */
    public function getErrors(): object;

    /**
     * @return void
     */
    public function resetErrors(): void;

    /**
     * @return string
     */
    public function getName(): string;

    /**
     * @return bool
     */
    public function isRequired(): bool;

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired(bool $required);

    /**
     * @return bool
     */
    public function isValid(): bool;

    public function validate(): void;
}