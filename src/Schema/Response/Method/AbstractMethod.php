<?php

namespace Ironex\Schema\Response\Method;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Parameter\ParameterInterface;
use Ironex\Schema\Response\Parameter\ScalarParameter\ScalarParameterInterface;

abstract class AbstractMethod implements MethodInterface
{
    /**
     * @var object
     */
    protected $errors;

    /**
     * @var ParameterInterface[]
     */
    protected $parameters = [];

    public function __construct()
    {
        $this->errors = (object) [];
    }

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
     * @return object
     */
    public function getDefinition(): object
    {
        $definition = (object) [];

        foreach($this->parameters as $parameter)
        {
            $definition->{$parameter->getName()} = $parameter->getDefinition();
        }

        return $definition;
    }

    /**
     * @return object
     */
    public function getErrors(): object
    {
        foreach($this->parameters as $parameter)
        {
            $parameterErrors = $parameter->getErrors();

            if($parameterErrors)
            {
                $this->errors->{$parameter->getName()} = $parameterErrors;
            }
        }

        return clone $this->errors;
    }

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        if (get_object_vars($this->errors))
        {
            return false;
        }

        foreach ($this->parameters as $parameter)
        {
            if (get_object_vars($parameter->getErrors()))
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @return object
     */
    public function serialize(): object
    {
        $data = (object) [];

        foreach($this->parameters as $parameter)
        {
            if($parameter instanceof ScalarParameterInterface)
            {
                $data->{$parameter->getName()} = $parameter->getValue();
            }
            else
            {
                $data->{$parameter->getName()} = $parameter->serialize();
            }
        }

        return $data;
    }

    public function validate(): void
    {
        foreach($this->parameters as $parameter)
        {
            if($parameter->isRequired() && $parameter->getValue() === null)
            {
                $parameter->addError(ErrorEnum::REQUIRED);
                continue;
            }

            /** @var ParameterInterface $parameter */
            $parameter->validate();
        }
    }
}