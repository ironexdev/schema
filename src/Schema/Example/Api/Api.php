<?php

namespace Ironex\Schema\Example\Api;

use DI\Annotation\Inject;
use Ironex\Schema\AbstractApi;
use Ironex\Schema\Example\Api\Test\TestResource;
use Ironex\Schema\Example\Response;

class Api extends AbstractApi
{
    /**
     * @var array
     */
    protected $resources = [
        TestResource::class
    ];

    /**
     * @Inject
     * @var Response
     */
    private $response;

    public function options(): void
    {
        $this->response->setData($this->getDefinition());
        $this->response->send(200, ["Access-Control-Allow-Methods" => "POST, DELETE, OPTIONS, GET, PUT"]);
    }
}