<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\MaxLengthRule;
use Ironex\Schema\Request\Parameter\ScalarParameter\Rule\MinLengthRule;
use Error;

class StringParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::STRING;

    /**
     * @var MaxLengthRule
     */
    private $maxLengthRule;

    /**
     * @var MinLengthRule
     */
    private $minLengthRule;

    /**
     * @var string
     */
    private $value;

    /**
     * Parameter constructor.
     * @param string $name
     * @param MaxLengthRule $maxLengthRule
     * @param MinLengthRule $minLengthRule
     */
    public function __construct(string $name, MaxLengthRule $maxLengthRule, MinLengthRule $minLengthRule)
    {
        parent::__construct($name);

        $this->maxLengthRule = $maxLengthRule;
        $this->minLengthRule = $minLengthRule;
    }

    /**
     * @param int $maxLength
     * @return $this
     */
    public function setMaxLength(int $maxLength): self
    {
        $this->maxLengthRule->setConstraint($maxLength);
        $this->rules[] = $this->maxLengthRule;
        return $this;
    }

    /**
     * @param int $minLength
     * @return $this
     */
    public function setMinLength(int $minLength): self
    {
        $this->minLengthRule->setConstraint($minLength);
        $this->rules[] = $this->minLengthRule;
        return $this;
    }

    /**
     * @return string
     */
    public function getValue(): string
    {
        return $this->value;
    }

    /**
     * @param string $value
     * @return $this
     */
    public function setValue($value)
    {
        if(gettype($value) !== ParameterTypeEnum::STRING)
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::STRING . ", " . gettype($value) . " given");
        }

        $this->value = $value;

        return $this;
    }
}