<?php

namespace Ironex\Schema\Request\Parameter\ObjectParameter;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ArrayParameter\ArrayParameter;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\ScalarParameterInterface;

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
     * @param string $error
     * @param true $constraint
     * @return $this
     */
    public function addError(string $error, $constraint = true)
    {
        $this->errors[$error] = $constraint;

        return $this;
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
     * @param $input
     */
    public function setValues($input): void
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
        if(gettype($input) !== ParameterTypeEnum::OBJECT)
        {
            $this->errors[ErrorEnum::TYPE] = ParameterTypeEnum::OBJECT;
            return;
        }

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