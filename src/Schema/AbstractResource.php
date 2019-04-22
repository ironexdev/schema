<?php

namespace Ironex\Schema;

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Error;
use Exception;
use Ironex\Schema\Request\Method\MethodInterface;

abstract class AbstractResource implements ResourceInterface
{
    /**
     * @var array
     */
    protected $requestMethods = [];

    /**
     * @return MethodInterface[]
     */
    public function getRequestMethods(): array
    {
        $containerBuilder = new ContainerBuilder;
        $containerBuilder->useAutowiring(true);
        $containerBuilder->useAnnotations(true);

        try
        {
            $container = $containerBuilder->build();
        }
        catch (Exception $e)
        {
            throw new Error($e->getMessage());
        }

        $requestMethodObjects = [];
        foreach($this->requestMethods as $requestMethod)
        {
            try
            {
                $requestMethodName = strtolower(str_replace("RQ", "", substr($requestMethod, strrpos($requestMethod, "\\") + 1)));
                $requestMethodObjects[$requestMethodName] = $container->get($requestMethod);
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
            if ($method !== "options" && in_array($method, $crudMethods))
            {
                $methods[] = $method;
            }
        }

        return $methods;
    }
}