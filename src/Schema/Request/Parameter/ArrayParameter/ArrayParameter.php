<?php

namespace Ironex\Schema\Request\Parameter\ArrayParameter;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ParameterInterface;

class ArrayParameter implements ParameterInterface
{
    /**
     * @var array
     */
    private $errors;

    /**
     * @var array
     */
    private $items;

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
     * @param $input
     */
    public function validateInput($input): void
    {
        if(gettype($input) !== $this->type)
        {
            $this->errors[ErrorEnum::TYPE] = $this->type;
            return;
        }

        $inputCount = count($input);

        if($this->maxItemCount !== null && $inputCount > $this->maxItemCount)
        {
            $this->errors[ErrorEnum::MAX_ITEM_COUNT] = $this->maxItemCount;
            return;
        }

        if($this->minItemCount !== null && $inputCount < $this->minItemCount)
        {
            $this->errors[ErrorEnum::MIN_ITEM_COUNT] = $this->minItemCount;
            return;
        }

        for($i = 0; $i < $inputCount; $i++)
        {
            $value = $input[$i];
            $this->parameter->resetErrors();
            $this->parameter->validateInput($value);

            if(!$this->parameter->isValid())
            {
                $this->errors[$i] = $this->parameter->getErrors();
                continue;
            }

            $this->items[$i] = $value;
        }
    }
}