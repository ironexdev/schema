<?php

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ArrayParameter\ArrayParameterInterface;
use Ironex\Schema\Request\Parameter\ObjectParameter\ObjectParameterInterface;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\ScalarParameterInterface;

abstract class AbstractObjectParameter implements ObjectParameterInterface, ParameterInterface
{
    /**
     * @var object
     */
    protected $errors;

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
        $this->errors = (object) [];
        $this->name = $name;
    }

    /**
     * @return object
     */
    public function getDefinition(): object
    {
        $definition = (object) [];

        $definition->{ErrorEnum::REQUIRED} = $this->isRequired();
        $definition->{ErrorEnum::TYPE} = ParameterTypeEnum::OBJECT;

        if($this->parameters)
        {
            $definition->parameters = (object) [];
            foreach($this->parameters as $parameter)
            {
                $definition->parameters->{$parameter->getName()} = $parameter->getDefinition();
            }
        }

        return $definition;
    }

    /**
     * @param string $error
     * @param bool $constraint
     */
    public function addError(string $error, $constraint = true): void
    {
        $this->errors->{$error} = $constraint;
    }

    /**
     * @return object
     */
    public function getErrors(): object
    {
        $errorsObject = (object) [];

        if(get_object_vars($this->errors))
        {
            $errorsObject->errors = $this->errors;
            return $errorsObject;
        }

        foreach($this->parameters as $parameter)
        {
            $parameterErrors = $parameter->getErrors();

            if($parameterErrors)
            {
                $this->errors->parameters->{$parameter->getName()} = $parameterErrors;
            }
        }

        $errorsObject->errors = $this->errors;
        return $errorsObject;
    }

    public function resetErrors(): void
    {
        $this->errors = (object) [];
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

    /**
     * @param $input
     */
    public function validateInput($input): void
    {
        if(gettype($input) !== ParameterTypeEnum::OBJECT)
        {
            $this->errors->{ErrorEnum::TYPE} = ParameterTypeEnum::OBJECT;
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