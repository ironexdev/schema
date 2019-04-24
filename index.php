<?php

use Ironex\Schema\Example\Api\Api;
use Ironex\Schema\Example\Api\Test\TestResource;
use DI\ContainerBuilder;

require __DIR__ . DIRECTORY_SEPARATOR . "vendor" . DIRECTORY_SEPARATOR . "autoload.php";
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);

error_reporting(E_ALL);

register_shutdown_function("fatal_handler");

function fatal_handler()
{
    $error = error_get_last();

    if($error)
    {
        handleError($error);
    }
}

try
{
    init();
}
catch (Throwable $e)
{
    handleError([
                    "message" => $e->getMessage(),
                    "file" => $e->getFile(),
                    "line" => $e->getLine(),
                    "trace" => $e->getTrace()
                ]);
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

    if ($_SERVER["REQUEST_URI"] === "/schema" && $requestMethod === "OPTIONS")
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

function handleError(array $error)
{
    http_response_code(500);
    header("Content-Type: application/json; charset=utf-8");

    $responseObject = new stdClass();
    if (false) // production environment
    {
        $responseObject->message = "Page Not Found";
    }
    else
    {
        $responseObject->message = $error["message"] . " on line " . $error["line"] . " in file " . $error["file"];
        $responseObject->debug_backtrace = isset($error["trace"]) ? $error["trace"] : null;
    }

    echo json_encode($responseObject);
}