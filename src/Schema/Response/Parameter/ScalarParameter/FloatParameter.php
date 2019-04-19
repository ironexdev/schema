<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter;

use Ironex\Schema\Response\Enum\ParameterTypeEnum;
use Error;

class FloatParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::FLOAT;

    /**
     * @var float
     */
    protected $value;

    /**
     * @return float
     */
    public function getValue(): float
    {
        return $this->value;
    }

    /**
     * @param float $input
     */
    public function setValue($input): void
    {
        if(gettype($input) !== "double")
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::FLOAT . ", " . gettype($input) . " given");
        }

        $this->value = $input;
    }
}