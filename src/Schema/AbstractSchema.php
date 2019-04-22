<?php

namespace Ironex\Schema;

use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;
use Error;
use Exception;

abstract class AbstractSchema
{
    /**
     * @var array
     */
    protected $resources = [];

    /**
     * @return array
     */
    public function getDefinition(): array
    {
        $definition = [];

        foreach($this->getResources() as $resource)
        {
            $methods = $resource->getRequestMethods();

            $methodDefinitions = [];
            foreach($methods as $methodName => $method)
            {
                $methodDefinitions[$methodName] = $method->getDefinition();
            }

            $resourceClass = get_class($resource);
            $resourceName = strtolower(str_replace("Resource", "", substr($resourceClass, strrpos($resourceClass, "\\") + 1)));
            $definition[$resourceName] = $methodDefinitions;
        }

        return $definition;
    }

    /**
     * @return ResourceInterface[]
     */
    private function getResources(): array
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

        $resourceObjects = [];
        foreach($this->resources as $resource)
        {
            try
            {
                $resourceObjects[$resource] = $container->get($resource);
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