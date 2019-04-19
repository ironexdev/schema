<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter;

use Ironex\Schema\Response\Enum\ErrorEnum;
use Ironex\Schema\Response\Parameter\ParameterInterface;
use Ironex\Schema\Response\Parameter\ScalarParameter\Rule\RuleInterface;

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
    protected $rules = [];

    /**
     * @var string
     */
    protected $type;

    /**
     * @var mixed
     */
    protected $value;

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

    /**
     * @return bool
     */
    public function isValid(): bool
    {
        return $this->errors ? true : false;
    }

    public function validate(): void
    {
        foreach ($this->rules as $rule)
        {
            if (!$rule->test($this->value))
            {
                $this->errors[$rule->getName()] = $rule->getConstraint();
            }
        }
    }
}