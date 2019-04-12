<?php

namespace App\IronexSchema\Request\Parameter\ScalarParameter;

use App\IronexSchema\Request\Enum\ParameterTypeEnum;
use Error;

class IntegerParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::INTEGER;

    /**
     * @var int
     */
    private $value;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $value
     * @return $this
     */
    public function setValue($value)
    {
        if(gettype($value) !== ParameterTypeEnum::INTEGER)
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::INTEGER . ", " . gettype($value) . " given");
        }

        $this->value = $value;

        return $this;
    }
}