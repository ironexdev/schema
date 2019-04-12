<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter;

use App\IronexSchema\Request\Enum\ParameterTypeEnum;
use Error;

class FloatParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::FLOAT;

    /**
     * @var float
     */
    private $value;

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $value
     * @return $this
     */
    public function setValue($value)
    {
        if(gettype($value) !== "double")
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::FLOAT . ", " . gettype($value) . " given");
        }

        $this->value = $value;

        return $this;
    }
}