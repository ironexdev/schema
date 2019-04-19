<?php

namespace Ironex\Schema\Response\Parameter\ScalarParameter;

use Ironex\Schema\Response\Enum\ParameterTypeEnum;
use Error;

class IntegerParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::INTEGER;

    /**
     * @var int
     */
    protected $value;

    /**
     * @return int
     */
    public function getValue(): int
    {
        return $this->value;
    }

    /**
     * @param int $input
     */
    public function setValue($input): void
    {
        if(gettype($input) !== ParameterTypeEnum::INTEGER)
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::INTEGER . ", " . gettype($input) . " given");
        }

        $this->value = $input;
    }
}