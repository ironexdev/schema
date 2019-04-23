<?php

use Ironex\Schema\Example\Api\Api;
use Ironex\Schema\Example\Api\Test\TestResource;
use DI\ContainerBuilder;

require __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";

try
{
    init();
}
catch (Throwable $e)
{
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");

    if (false) // production environment
    {
        echo json_encode([
                             "message" => "Page Not Found"
                         ]);
    }
    else
    {
        echo json_encode([
                             "message" => $e->getMessage() . " on line " . $e->getLine() . " in file " . $e->getFile(),
                             "debug_backtrace" => $e->getTrace()
                         ]);
    }
}

/**
 * @throws Exception
 */
function init()
{
    $containerBuilder = new ContainerBuilder;
    $containerBuilder->useAutowiring(true);
    $containerBuilder->useAnnotations(true);
    $container = $containerBuilder->build();

    $requestToCrudMethod = [
        "POST" => "create",
        "DELETE" => "delete",
        "OPTIONS" => "options",
        "GET" => "read",
        "PUT" => "update",
    ];

    $requestMethod = $_SERVER["REQUEST_METHOD"];

    if($_SERVER["REQUEST_URI"] === "/schema" && $requestMethod === "OPTIONS")
    {
        $container->call([
                             Api::class,
                             "options"
                         ]);
    }
    else
    {
        $container->call([
                             TestResource::class,
                             $requestToCrudMethod[$requestMethod]
                         ]);
    }
}