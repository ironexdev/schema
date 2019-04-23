<?php

namespace Ironex\Schema\Example\Api;

use Ironex\Schema\AbstractApi;
use Ironex\Schema\Example\Api\Test\TestResource;

class Api extends AbstractApi
{
    /**
     * @var array
     */
    protected $resources = [
        TestResource::class
    ];

    public function options(): void
    {
        http_response_code(200);
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: POST, DELETE, OPTIONS, GET, PUT");
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
                             "data" => $this->getDefinition(),
                             "errors" => [],
                             "status" => true,
                         ]);
        exit;
    }
}