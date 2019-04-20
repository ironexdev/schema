<?php

namespace Ironex\Schema\Example\Api\Test\Read;

use Ironex\Schema\Response\Method\AbstractMethod;
use Ironex\Schema\Response\Parameter\ScalarParameter\Factory\IntegerParameterFactory;
use Ironex\Schema\Response\Parameter\ScalarParameter\IntegerParameter;

class ReadRS extends AbstractMethod
{
    /**
     * @var IntegerParameter
     */
    private $idParameter;

    /**
     * Parameter constructor.
     * @param IntegerParameterFactory $integerParameterFactory
     */
    public function __construct(IntegerParameterFactory $integerParameterFactory)
    {
        $this->idParameter = $this->addParameter($integerParameterFactory->create("id")
                                                                         ->setRequired(true));
    }

    /**
     * @return IntegerParameter
     */
    public function getIdParameter(): IntegerParameter
    {
        return $this->idParameter;
    }
}