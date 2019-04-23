<?php

namespace Ironex\Schema\Example\Api\Test;

use Ironex\Schema\Example\Api\AbstractResource;
use Ironex\Schema\Example\Api\Test\Read\ReadRQ;
use Ironex\Schema\Example\Api\Test\Read\ReadRS;

class TestResource extends AbstractResource
{
    protected $requestMethods = [
        ReadRQ::class
    ];

    protected $responseMethods = [
        ReadRS::class
    ];

    /**
     * @param ReadRQ $readRQ
     * @param ReadRS $readRS
     */
    public function read(ReadRQ $readRQ, ReadRS $readRS): void
    {
        $this->initRQ($readRQ);

        $readRS->getIdParameter()
               ->setValue(1);

        $this->sendRS($readRS);
    }
}