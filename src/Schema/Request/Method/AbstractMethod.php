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
        if(get_object_vars($this->errors))
        {
            return false;
        }

        foreach($this->parameters as $parameter)
        {
            if(get_object_vars($parameter->getErrors()))
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
     * @param object $input
     */
    public function validateInput(object $input): void
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