<?php

namespace Ironex\Schema;

use DI\Annotation\Inject;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Error;
use Ironex\Schema\Request\Method\MethodInterface;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var array
     */
    protected $requestMethods = [];

    /**
     * @var array
     */
    protected $responseMethods = [];

    /**
     * @Inject
     * @var Container
     */
    private $container;

    /**
     * @return MethodInterface[]
     */
    public function getRequestMethods(): array
    {
        $requestMethodObjects = [];
        foreach($this->requestMethods as $requestMethod)
        {
            try
            {
                $requestMethodName = strtolower(str_replace("RQ", "", substr($requestMethod, strrpos($requestMethod, "\\") + 1)));
                $requestMethodObjects[$requestMethodName] = $this->container->get($requestMethod);
            }
            catch (DependencyException $e)
            {
                throw new Error($e->getMessage());
            }
            catch (NotFoundException $e)
            {
                throw new Error($e->getMessage());
            }
        }

        return $requestMethodObjects;
    }

    /**
     * @return array
     */
    public function getRequestMethodNames(): array
    {
        $allMethods = get_class_methods($this);
        $crudMethods = ["create", "delete", "options", "read", "update"];
        $methods = [];

        foreach ($allMethods as $method)
        {
            if (in_array($method, $crudMethods))
            {
                $methods[] = $method;
            }
        }

        return $methods;
    }

    /**
     * @return MethodInterface[]
     */
    public function getResponseMethods(): array
    {
        $responseMethodObjects = [];
        foreach($this->responseMethods as $responseMethod)
        {
            try
            {
                $responseMethodName = strtolower(str_replace("RS", "", substr($responseMethod, strrpos($responseMethod, "\\") + 1)));
                $responseMethodObjects[$responseMethodName] = $this->container->get($responseMethod);
            }
            catch (DependencyException $e)
            {
                throw new Error($e->getMessage());
            }
            catch (NotFoundException $e)
            {
                throw new Error($e->getMessage());
            }
        }

        return $responseMethodObjects;
    }
}