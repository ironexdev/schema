<?php

namespace Ironex\Schema\Response\Parameter\ArrayParameter;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Enum\ParameterTypeEnum;
use Ironex\Schema\Response\Parameter\ParameterInterface;
use Ironex\Schema\Response\Parameter\ScalarParameter\ScalarParameterInterface;

class ArrayParameter implements ArrayParameterInterface, ParameterInterface
{
    /**
     * @var array
     */
    private $errors = [];

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
        $this->name = $name;
        $this->parameter = $parameter;
    }

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        $definition = [];

        $definition[ErrorEnum::REQUIRED] = $this->isRequired();
        $definition[ErrorEnum::TYPE] = $this->type;
        $definition["parameter"] = $this->parameter->getDefinition();

        return $definition;
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
    public function getErrors(): array
    {
        if(!$this->errors)
        {
            return [];
        }

        return [
            "errors" => $this->errors
        ];
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

    public function isValid(): bool
    {
        return $this->errors ? true : false;
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

    public function validate(): void
    {
        $parameterCount = count($this->parameters);

        if($this->maxItemCount !== null && $parameterCount > $this->maxItemCount)
        {
            $this->errors[ErrorEnum::MAX_ITEM_COUNT] = $this->maxItemCount;
            return;
        }

        if($this->minItemCount !== null && $parameterCount < $this->minItemCount)
        {
            $this->errors[ErrorEnum::MIN_ITEM_COUNT] = $this->minItemCount;
            return;
        }

        for($i = 0; $i < $parameterCount; $i++)
        {
            $parameter = $this->parameters[$i];
            $parameter->validate();

            if(!$parameter->isValid())
            {
                $this->errors[$i] = $parameter->getErrors();
                continue;
            }
        }
    }
}