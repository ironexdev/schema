<?php

namespace Ironex\Schema\Request\Parameter\ScalarParameter;

use Ironex\Schema\Request\Enum\ParameterTypeEnum;
use Error;

class BooleanParameter extends AbstractScalarParameter
{
    protected $type = ParameterTypeEnum::BOOLEAN;

    /**
     * @var bool
     */
    private $value;

    /**
     * @return bool
     */
    public function isValue(): bool
    {
        return $this->value;
    }

    /**
     * @param bool $input
     */
    public function setValue($input): void
    {
        if(gettype($input) !== ParameterTypeEnum::BOOLEAN)
        {
            throw new Error("Value must be of the type " . ParameterTypeEnum::BOOLEAN . ", " . gettype($input) . " given");
        }

        $this->value = $input;
    }
}