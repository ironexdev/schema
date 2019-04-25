<?php

namespace Ironex\Schema;

use DI\Annotation\Inject;
use DI\Container;
use DI\DependencyException;
use DI\NotFoundException;
use Error;
use Ironex\Schema\Enum\RequestMethodEnum;

abstract class AbstractApi
{
    /**
     * @var array
     */
    protected $resources = [];

    /**
     * @Inject
     * @var Container
     */
    private $container;

    /**
     * @return array
     */
    public function getAllowedRequestMethods(): array
    {
        $allowedRequestMethods = [];

        foreach($this->getResources() as $resource)
        {
            foreach($resource->getRequestMethodNames() as $requestMethod)
            {
                $requestMethod = RequestMethodEnum::CRUD_TO_REQUEST_METHOD[$requestMethod];

                if(!in_array($requestMethod, $allowedRequestMethods))
                {
                    $allowedRequestMethods[] = $requestMethod;
                }
            }
        }

        return $allowedRequestMethods;
    }

    /**
     * @return object
     */
    public function getDefinition(): object
    {
        $definition = (object) [];

        foreach($this->getResources() as $resource)
        {
            $resourceClass = get_class($resource);
            $resourceName = "/" . strtolower(str_replace("Resource", "", substr($resourceClass, strrpos($resourceClass, "\\") + 1)));

            $requestMethods = $resource->getRequestMethods();
            $responseMethods = $resource->getResponseMethods();

            if(count($requestMethods) !== count($responseMethods))
            {
                throw new Error($resourceClass . " request method count does not match response method count");
            }

            $methodDefinitions = (object) [];
            foreach($requestMethods as $requestMethodName => $requestMethod)
            {
                $methodDefinitions->{RequestMethodEnum::CRUD_TO_REQUEST_METHOD[$requestMethodName]} = (object) [
                    "request" => $requestMethod->getDefinition(),
                    "response" => $responseMethods[$requestMethodName]->getDefinition()
                ];
            }

            $definition->$resourceName = $methodDefinitions;
        }

        return $definition;
    }

    /**
     * @return ResourceInterface[]
     */
    private function getResources(): array
    {
        $resourceObjects = [];
        foreach($this->resources as $resource)
        {
            try
            {
                $resourceObjects[$resource] = $this->container->get($resource);
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

        return $resourceObjects;
    }
}