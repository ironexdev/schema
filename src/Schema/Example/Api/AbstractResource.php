<?php

namespace Ironex\Schema\Example\Api;

use DI\Annotation\Inject;
use Error;
use Ironex\Schema\AbstractResource as IronexAbstractResource;
use Ironex\Schema\Example\Response;
use Ironex\Schema\Request\Method\MethodInterface as MethodInterfaceRQ;
use Ironex\Schema\Response\Method\MethodInterface as MethodInterfaceRS;

class AbstractResource extends IronexAbstractResource
{
    /**
     * @Inject
     * @var Response
     */
    protected $response;

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

        $this->response->send(200, ["Access-Control-Allow-Methods" => implode(", ", $allowedRequestMethods)]);
        exit;
    }

    /**
     * @param MethodInterfaceRQ $methodRQ
     */
    protected function initRQ(MethodInterfaceRQ $methodRQ): void
    {
        $requestData = json_decode(file_get_contents("php://input"));

        $methodRQ->validateInput($requestData);

        if (!$methodRQ->isValid())
        {
           $this->response->setErrors($methodRQ->getErrors());
           $this->response->setStatus(false);
           $this->response->send();
        }

        $methodRQ->setValues($requestData);
    }

    /**
     * @param MethodInterfaceRS $methodRS
     */
    protected function sendRS(MethodInterfaceRS $methodRS): void
    {
        $methodRS->validate();

        if (!$methodRS->isValid())
        {
            if(true) // development environment
            {
                $this->response->setErrors($methodRS->getErrors());
                $this->response->setStatus(false);
                $this->response->send(500);
            }
            else
            {
                // log errors
                throw new Error("methodRS contains errors - check log for more information");
            }
        }

        $this->response->setData($methodRS->serialize());
        $this->response->send();
    }
}