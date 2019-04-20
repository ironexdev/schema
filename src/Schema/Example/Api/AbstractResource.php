<?php

namespace Ironex\Schema\Example\Api;

use Ironex\Schema\Example\Api\Test\Read\ReadRQ;
use Ironex\Schema\Example\Api\Test\Read\ReadRS;

abstract class AbstractResource
{
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
        header("Content-Type: application/json");

        echo json_encode($response);
        exit;
    }
}