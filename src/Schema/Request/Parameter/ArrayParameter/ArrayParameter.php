<?php

namespace Ironex\Schema\Request\Parameter\ArrayParameter;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\ScalarParameterInterface;

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
     * @param array $input
     */
    public function setValues(array $input): void
    {
        foreach($input as $value)
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
     * @param $input
     */
    public function validateInput($input): void
    {
        if(gettype($input) !== $this->type)
        {
            $this->errors->{ErrorEnum::TYPE} = $this->type;
            return;
        }

        $inputCount = count($input);

        if($this->maxItemCount !== null && $inputCount > $this->maxItemCount)
        {
            $this->errors->{ErrorEnum::MAX_ITEM_COUNT} = $this->maxItemCount;
            return;
        }

        if($this->minItemCount !== null && $inputCount < $this->minItemCount)
        {
            $this->errors->{ErrorEnum::MIN_ITEM_COUNT} = $this->minItemCount;
            return;
        }

        for($i = 0; $i < $inputCount; $i++)
        {
            $value = $input[$i];
            $this->parameter->resetErrors();
            $this->parameter->validateInput($value);

            if(!$this->parameter->isValid())
            {
                $this->errors["item" . $i] = $this->parameter->getErrors();
                continue;
            }
        }
    }
}