<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\RuleInterface;

abstract class AbstractScalarParameter implements ScalarParameterInterface, ParameterInterface
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
     * @var RuleInterface[]
     */
    protected $rules = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * AbstractScalarParameter constructor.
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
        $definition->{ErrorEnum::TYPE} = $this->type;

        $definition->rules = (object) [];
        foreach($this->rules as $rule)
        {
            $definition->rules->{$rule->getName()} = $rule->getConstraint();
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
     * @param $input
     */
    public function validateInput($input): void
    {
        if(gettype($input) !== ($this->type !== ParameterTypeEnum::FLOAT ? $this->type : "double"))
        {
            $this->errors->{ErrorEnum::TYPE} = $this->type;
            return;
        }

        foreach ($this->rules as $rule)
        {
            if (!$rule->test($input))
            {
                $this->errors->{$rule->getName()} = $rule->getConstraint();
            }
        }
    }
}