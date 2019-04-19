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
     * @return array
     */
    public function getDefinition(): array;

    /**
     * @return array
     */
    public function getErrors(): array;

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