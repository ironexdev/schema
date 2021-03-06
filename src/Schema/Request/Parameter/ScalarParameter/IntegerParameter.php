<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

use Ironex\Schema\Request\Enum\ParameterTypeEnum;
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