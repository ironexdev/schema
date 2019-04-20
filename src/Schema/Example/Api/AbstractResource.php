<?php

namespace Ironex\Schema\Example\Api;

use Ironex\Schema\Example\Api\Test\Read\ReadRQ;
use Ironex\Schema\Example\Api\Test\Read\ReadRS;

abstract class AbstractResource
{
    public function options(): void
    {
        $allowedMethods = [];
        $crudMethods = ["create", "delete", "options", "read", "update"];
        $crudToRequestMethod = [
            "create" => "POST",
            "delete" => "DELETE",
            "options" => "OPTIONS",
            "read" => "GET",
            "update" => "PUT"
        ];
        $methods = get_class_methods($this);

        foreach ($methods as $method)
        {
            if ($method !== "options" && in_array($method, $crudMethods))
            {
                $allowedMethods[] = $crudToRequestMethod[$method];
            }
        }

        http_response_code(200);
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: " . implode(", ", $allowedMethods));
        header("Access-Control-Allow-Origin: *");
        header("Content-Type: application/json; charset=utf-8");
        echo json_encode([
                             "data" => [],
                             "errors" => [],
                             "status" => true,
                         ]);
        exit;
    }

    /**
     * @param ReadRQ $readRQ
     */
    protected function initRQ(ReadRQ $readRQ): void
    {
        $requestData = json_decode(file_get_contents("php://input"));

        $readRQ->validateInput($requestData);
        if (!$readRQ->isValid())
        {
            $this->send([
                            "data" => [],
                            "errors" => $readRQ->getErrors(),
                            "status" => false,
                        ], 200);
        }

        $readRQ->setValues($requestData);
    }

    /**
     * @param ReadRS $readRS
     */
    protected function sendRS(ReadRS $readRS): void
    {
        $readRS->validate();
        if (!$readRS->isValid())
        {
            $this->send([
                            "message" => $readRS->getErrors()
                        ], 500);
        }

        $this->send([
                        "data" => $readRS->toArray(),
                        "errors" => [],
                        "status" => true
                    ], 200);
    }

    /**
     * @param array $response
     * @param int $code
     */
    protected function send(array $response, int $code = 200)
    {
        http_response_code($code);
        header("Content-Type: application/json; charset=utf-8");

        echo json_encode($response);
        exit;
    }
}