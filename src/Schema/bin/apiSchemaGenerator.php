<?php

use DI\Container;
use DI\ContainerBuilder;
use DI\DependencyException;
use DI\NotFoundException;

require __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

(function () {
	$apiDirectory = __DIR__ . DIRECTORY_SEPARATOR;
	$ruleDirectory = $apiDirectory . DIRECTORY_SEPARATOR . "Schema" . DIRECTORY_SEPARATOR . "Rule";
	$schema = [
		"structure" => getPresenters($apiDirectory),
		"ruleList" => getRuleList($ruleDirectory),
	];

	$schemaJson = json_encode($schema, JSON_PRETTY_PRINT);

	$schemaFilePath = __DIR__ . DIRECTORY_SEPARATOR;
	saveFile($schemaFilePath, $schemaJson);

	echo "schema has been generated to " . $schemaFilePath;
})();

/**
 * @param string $apiDirectory
 * @return array
 * @throws DependencyException
 * @throws NotFoundException
 * @throws Exception
 */
function getPresenters(string $apiDirectory): array
{
	$container = buildContainer();
	$presenters = [];
	$apiFiles = readDirectory($apiDirectory, false);

	$presenterDirectories = [];
	foreach ($apiFiles as $file) {
		if (is_dir($file) && strpos($file, "Presenter")) {
			$presenterDirectories[] = $file;
		}
	}

	foreach ($presenterDirectories as $presenterDirectory) {
		$presenterName = strtolower(str_replace("Presenter", "", basename($presenterDirectory, ".php")));
		$presenterDirectoryFiles = readDirectory($presenterDirectory);

		$presenters[$presenterName] = getMethods($presenterName, $presenterDirectory, $presenterDirectoryFiles);
	}

	return $presenters;
}

/**
 * @param string $presenterName
 * @param string $presenterDirectory
 * @param array $presenterDirectoryFiles
 * @return array
 * @throws DependencyException
 * @throws NotFoundException
 * @throws Exception
 */
function getMethods(string $presenterName, string $presenterDirectory, array $presenterDirectoryFiles): array
{
	$container = buildContainer();
	$methods = [];
	$presenterExists = false;
	foreach ($presenterDirectoryFiles as $file) {
		if (strpos($file, "Presenter")) {
			$presenterExists = true;
		}

		$methodName = strtolower(basename($file, ".php"));

		if (in_array($methodName, ["create", "read", "update", "delete"], true)) {
			/**
			 * @var ApiMethodInterface $methodClass
			 */
			$method = $container->get(fileNameToNamespace($file));

			$methods[$methodName] = getParameters($method);
		}
	}

	if (!$presenterExists) {
		throw new Error($presenterDirectory . " does not contain a " . $presenterName);
	}

	return $methods;
}

/**
 * @param ApiMethodInterface $method
 * @return AbstractParameter[]
 */
function getParameters(ApiMethodInterface $method): array
{
	$parameters = [];

	$method->define();

	foreach ($method->getSchema()->getParameters() as $parameter) {
		$parameters[$parameter->getName()] = getRules($parameter);
	}

	return $parameters;
}

/**
 * @param AbstractParameter $parameter
 * @return RuleInterface[]
 */
function getRules(AbstractParameter $parameter): array
{
	$rules = [];

	foreach ($parameter->getRules() as $rule) {
		$rules[$rule->getName()] = $rule->getConstraint();
	}

	return $rules;
}

/**
 * @param string $ruleDirectory
 * @return array
 * @throws DependencyException
 * @throws NotFoundException
 * @throws Exception
 */
function getRuleList(string $ruleDirectory): array
{
	$container = buildContainer();

	$rules = [];

	$files = readDirectory($ruleDirectory);

	foreach ($files as $file) {
		if(strpos($file, "Interface") !== false || strpos($file, "Abstract") !== false || strpos($file, "Factory") !== false)
		{
			continue;
		}

		/**
		 * @var RuleInterface $rule
		 */
		$rule = $container->get(fileNameToNamespace($file));

		$rules[] = $rule->getName();
	}

	return $rules;
}

/**
 * @param string $file
 * @return string
 */
function fileNameToNamespace(string $file): string
{
	/*
	 * select everything after (including) src
	 * replace src with App
	 * remove .php
	 * replace slashes with backslashes
	 */
	return str_replace("/", "\\", str_replace(".php", "", str_replace("src", "App", substr($file, strpos($file, "src")))));
}

/**
 * @param string $directory
 * @param bool $excludeDirectories
 * @return array
 */
function readDirectory(string $directory, bool $excludeDirectories = true): array
{
    $content = scandir($directory);

    if (!$content)
    {
        return [];
    }
    $files = [];

    foreach ($content as $key => $value)
    {
        $path = realpath($directory . DIRECTORY_SEPARATOR . $value);
        if (!is_dir($path))
        {
            $files[] = $path;
        }
        else if ($value !== "." && $value !== "..")
        {
            if (!$excludeDirectories)
            {
                $files[] = $path;
            }

            $files = array_merge($files, $this->readDirectory($path, $excludeDirectories));
        }
    }

    return $files;
}

/**
 * @param string $filePath
 * @param string $data
 * @param bool $overwrite
 * @return bool
 */
function saveFile(string $filePath, string $data, bool $overwrite = true): bool
{
    $flags = !$overwrite ? FILE_APPEND : 0;

    if (@file_put_contents($filePath, $data, $flags))
    {
        return true;
    }

    return false;
}

/**
 * @return Container
 * @throws Exception
 */
function buildContainer(): Container
{
	$configPath = __DIR__ . DIRECTORY_SEPARATOR;
	$containerBuilder = new ContainerBuilder;
	$containerBuilder->useAutowiring(true);
	$containerBuilder->useAnnotations(true);
	$containerBuilder->addDefinitions($configPath);

	return $containerBuilder->build();
}