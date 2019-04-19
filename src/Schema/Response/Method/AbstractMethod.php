<?php

namespace Ironex\Schema\Response\Method;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Parameter\ParameterInterface;

abstract class AbstractMethod implements MethodInterface
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var ParameterInterface[]
     */
    protected $parameters = [];

    /**
     * @param ParameterInterface $parameter
     * @return ParameterInterface
     */
    public function addParameter(ParameterInterface $parameter): ParameterInterface
    {
        $this->parameters[] = $parameter;

        return $parameter;
    }

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        $definition = [];

        foreach ($this->parameters as $parameter)
        {
            $definition[$parameter->getName()] = $parameter->getDefinition();
        }

        return $definition;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        $errors = $this->errors;

        foreach ($this->parameters as $parameter)
        {
            $parameterErrors = $parameter->getErrors();

            if ($parameterErrors)
            {
                $errors[$parameter->getName()] = $parameterErrors;
            }
        }

        return $errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        $errors = $this->errors;

        if ($errors)
        {
            return false;
        }

        foreach ($this->parameters as $parameter)
        {
            if ($parameter->getErrors())
            {
                return false;
            }
        }

        return true;
    }

    public function validate(): void
    {
        foreach($this->parameters as $parameter)
        {
            if($parameter->isRequired())
            {
                $parameter->addError(ErrorEnum::REQUIRED);
                continue;
            }

            /** @var MethodInterface $parameter */
            $parameter->validate();
        }
    }
}