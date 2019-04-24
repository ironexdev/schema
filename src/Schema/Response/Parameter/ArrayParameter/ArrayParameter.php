<?php

namespace Ironex\Schema\Response\Parameter\ArrayParameter;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Enum\ParameterTypeEnum;
use Ironex\Schema\Response\Parameter\ParameterInterface;
use Ironex\Schema\Response\Parameter\ScalarParameter\ScalarParameterInterface;

class ArrayParameter implements ArrayParameterInterface, ParameterInterface
{
    /**
     * @var object
     */
    private $errors;

    /**
     * @var int
     */
    private $maxItemCount;

    /**
     * @var int
     */
    private $minItemCount;

    /**
     * @var string
     */
    private $name;

    /**
     * @var ParameterInterface
     */
    private $parameter;

    /**
     * @var ParameterInterface[]
     */
    private $parameters = [];

    /**
     * @var bool
     */
    private $required;

    /**
     * @var string
     */
    private $type = ParameterTypeEnum::ARRAY;

    /**
     * Parameter constructor.
     * @param string $name
     * @param ParameterInterface $parameter
     */
    public function __construct(string $name, ParameterInterface $parameter)
    {
        $this->errors = (object) [];
        $this->name = $name;
        $this->parameter = $parameter;
    }

    /**
     * @return object
     */
    public function getDefinition(): object
    {
        $definition = (object) [];

        $definition->{ErrorEnum::REQUIRED} = $this->isRequired();
        $definition->{ErrorEnum::TYPE} = $this->type;
        $definition->parameter = $this->parameter->getDefinition();

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
        if(!get_object_vars($this->errors))
        {
            return $this->errors;
        }

        $errorsObject = (object) [];
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
        return get_object_vars($this->errors) ? true : false;
    }

    /**
     * @param array $values
     */
    public function setValues(array $values): void
    {
        foreach($values as $value)
        {
            $parameter = clone $this->parameter;

            if($this->parameter instanceof ScalarParameterInterface)
            {
                $parameter->setValue($value);
            }
            else if($this->parameter instanceof ArrayParameterInterface)
            {
                $parameter->setValues($value);
            }
            else
            {
                $parameter->setValues($value);
            }

            $this->parameters[] = $parameter;
        }
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
        $parameterCount = count($this->parameters);

        if($this->maxItemCount !== null && $parameterCount > $this->maxItemCount)
        {
            $this->errors->{ErrorEnum::MAX_ITEM_COUNT} = $this->maxItemCount;
            return;
        }

        if($this->minItemCount !== null && $parameterCount < $this->minItemCount)
        {
            $this->errors->{ErrorEnum::MIN_ITEM_COUNT} = $this->minItemCount;
            return;
        }

        for($i = 0; $i < $parameterCount; $i++)
        {
            $parameter = $this->parameters[$i];
            $parameter->validate();

            if(!$parameter->isValid())
            {
                $this->errors->{"item" . $i} = $parameter->getErrors();
                continue;
            }
        }
    }
}