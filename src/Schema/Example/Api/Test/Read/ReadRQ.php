<?php

namespace Ironex\Schema\Example\Api\Test\Read;

use Ironex\Schema\Request\Method\AbstractMethod;
use Ironex\Schema\Request\Parameter\ScalarParameter\Factory\StringParameterFactory;
use Ironex\Schema\Request\Parameter\ScalarParameter\StringParameter;

class ReadRQ extends AbstractMethod
{
    /**
     * @var StringParameter
     */
    private $titleParameter;

    /**
     * Parameter constructor.
     * @param StringParameterFactory $stringParameterFactory
     */
    public function __construct(StringParameterFactory $stringParameterFactory)
    {
        parent::__construct();

        $this->titleParameter = $this->addParameter($stringParameterFactory->create("title")
                                                                                 ->setRequired(true));
    }
    /**
     * @return StringParameter
     */
    public function getTitleParameter(): StringParameter
    {
        return $this->titleParameter;
    }
}