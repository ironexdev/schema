<?php

namespace Ironex\Schema\Request\Method;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Parameter\ArrayParameter\ArrayParameter;
use Ironex\Schema\Request\Parameter\ObjectParameter\ObjectParameterInterface;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\ScalarParameterInterface;

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

        foreach($this->parameters as $parameter)
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

        foreach($this->parameters as $parameter)
        {
            $parameterErrors = $parameter->getErrors();

            if($parameterErrors)
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

        if($errors)
        {
            return false;
        }

        foreach($this->parameters as $parameter)
        {
            if($parameter->getErrors())
            {
                return false;
            }
        }

        return true;
    }

    /**
     * @param object $input
     */
    public function setValues(object $input): void
    {
        foreach($this->parameters as $parameter)
        {
            $parameterName = $parameter->getName();

            if($parameter instanceof ScalarParameterInterface || $parameter instanceof ArrayParameter)
            {
                $this->{"get" . ucfirst($parameterName) . "Parameter"}()->setValue($input->$parameterName);
            }
            else
            {
                /** @var ObjectParameterInterface $parameter */
                $parameter->setValues($input->$parameterName);
            }
        }
    }

    /**
     * @param $input
     */
    public function validateInput($input): void
    {
        foreach($this->parameters as $parameter)
        {
            $parameterName = $parameter->getName();

            if(!property_exists($input, $parameterName))
            {
                if($parameter->isRequired())
                {
                    $parameter->addError(ErrorEnum::REQUIRED);
                    continue;
                }
                else
                {
                    continue;
                }
            }

            $value = $input->$parameterName;

            $parameter->validateInput($value);
        }
    }
}