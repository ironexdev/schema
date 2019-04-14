<?php

use DI\Container;
use DI\ContainerBuilder;

require __DIR__ . DIRECTORY_SEPARATOR . ".." . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

(function ($apiFolder) {
    var_dump($apiFolder);
})($argv[1]);

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