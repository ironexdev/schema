<?php

namespace Ironex\Schema;

use Ironex\Schema\Request\Method\MethodInterface;

interface ResourceInterface
{
    /**
     * @return MethodInterface[]
     */
    public function getRequestMethods(): array;

    /**
     * @return array
     */
    public function getRequestMethodNames(): array;
}