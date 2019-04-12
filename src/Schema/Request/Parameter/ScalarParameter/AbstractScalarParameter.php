<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

use Ironex\Schema\Request\Enum\ErrorEnum;
use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ParameterInterface;
use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\RuleInterface;

abstract class AbstractScalarParameter implements ScalarParameterInterface, ParameterInterface
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
     * @var RuleInterface[]
     */
    protected $rules;

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
        $this->name = $name;
    }

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        $definition = [];

        $definition[ErrorEnum::REQUIRED] = $this->isRequired();
        $definition[ErrorEnum::TYPE] = $this->type;

        $definition["rules"] = [];
        foreach($this->rules as $rule)
        {
            $definition["rules"][$rule->getName()] = $rule->getConstraint();
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

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->errors ? true : false;
    }

    /**
     * @param $input
     */
    public function validateInput($input): void
    {
        if(gettype($input) !== ($this->type !== ParameterTypeEnum::FLOAT ? $this->type : "double"))
        {
            $this->errors[ErrorEnum::TYPE] = $this->type;
            return;
        }

        foreach ($this->rules as $rule)
        {
            if (!$rule->test($input))
            {
                $this->errors[$rule->getName()] = $rule->getConstraint();
            }
        }
    }
}