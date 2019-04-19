<?php

namespace Ironex\Schema\Response\Parameter\ObjectParameter;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Enum\ParameterTypeEnum;
use Ironex\Schema\Response\Parameter\ArrayParameter\ArrayParameterInterface;
use Ironex\Schema\Response\Parameter\ParameterInterface;
use Ironex\Schema\Response\Parameter\ScalarParameter\ScalarParameterInterface;

abstract class AbstractObjectParameter implements ObjectParameterInterface, ParameterInterface
{
    /**
     * @var array
     */
    protected $errors = [];

    /**
     * @var string
     */
    protected $name;

    /**
     * @var ParameterInterface[]
     */
    protected $parameters = [];

    /**
     * @var bool
     */
    protected $required = false;

    /**
     * AbstractObjectParameter constructor.
     * @param string $name
     */
    public function __construct(string $name)
    {
        $this->name = $name;
    }

    /**
     * @param string $error
     * @param bool $constraint
     */
    public function addError(string $error, $constraint = true): void
    {
        $this->errors[$error] = $constraint;
    }

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        $definition = [];

        $definition[ErrorEnum::REQUIRED] = $this->isRequired();
        $definition[ErrorEnum::TYPE] = ParameterTypeEnum::OBJECT;

        foreach($this->parameters as $parameter)
        {
            $definition["parameters"][$parameter->getName()] = $parameter->getDefinition();
        }

        return $definition;
    }

    /**
     * @return array
     */
    public function getErrors(): array
    {
        if($this->errors)
        {
            return [
                "errors" => $this->errors
            ];
        }

        $errors = [];

        foreach($this->parameters as $parameter)
        {
            $parameterErrors = $parameter->getErrors();

            if($parameterErrors)
            {
                $errors["parameters"][$parameter->getName()] = $parameterErrors;
            }
        }

        return $errors;
    }

    public function resetErrors(): void
    {
        $this->errors = [];
    }

    /**
     * @return string
     */
    public function getName(): string
    {
        return $this->name;
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
     * @return bool
     */
    public function isRequired(): bool
    {
        return $this->required;
    }

    /**
     * @param bool $required
     * @return $this
     */
    public function setRequired(bool $required)
    {
        $this->required = $required;
        return $this;
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

            if($parameter instanceof ScalarParameterInterface)
            {
                $this->{"get" . ucfirst($parameterName) . "Parameter"}()->setValue($input->$parameterName);
            }
            else if($parameter instanceof ArrayParameterInterface)
            {
                $this->{"get" . ucfirst($parameterName) . "Parameter"}()->setValues($input->$parameterName);
            }
            else
            {
                $this->{"get" . ucfirst($parameterName) . "Parameter"}()->setValues($input->$parameterName);
            }
        }
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

            /** @var ParameterInterface $parameter */
            $parameter->validate();
        }
    }
}