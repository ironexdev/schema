<?php

namespace Ironex\Schema\Example\Api;

use Ironex\Schema\AbstractResource as IronexAbstractResource;
use Ironex\Schema\Request\Method\MethodInterface as MethodInterfaceRQ;
use Ironex\Schema\Response\Method\MethodInterface as MethodInterfaceRS;

class AbstractResource extends IronexAbstractResource
{
    public function options(): void
    {
        $allowedRequestMethods = [];
        $crudToRequestMethod = [
            "create" => "POST",
            "delete" => "DELETE",
            "options" => "OPTIONS",
            "read" => "GET",
            "update" => "PUT"
        ];

        $crudRequestMethods = $this->getRequestMethodNames();

        foreach ($crudRequestMethods as $crudRequestMethod)
        {
            $allowedRequestMethods[] = $crudToRequestMethod[$crudRequestMethod];
        }

        http_response_code(200);
        header("Access-Control-Allow-Headers: Content-Type");
        header("Access-Control-Allow-Methods: " . implode(", ", $allowedRequestMethods));
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
     * @param MethodInterfaceRQ $readRQ
     */
    protected function initRQ(MethodInterfaceRQ $readRQ): void
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
     * @param MethodInterfaceRS $readRS
     */
    protected function sendRS(MethodInterfaceRS $readRS): void
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