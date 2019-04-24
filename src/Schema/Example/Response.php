<?php

namespace Ironex\Schema\Example;

class Response
{
    /**
     * @var object
     */
    private $data;

    /**
     * @var object
     */
    private $errors;

    /**
     * @var bool
     */
    private $status = true;

    public function __construct()
    {
        $this->data = (object) [];
        $this->errors = (object) [];
    }

    /**
     * @param int $code
     * @param array $headers
     */
    public function send(int $code = 200, array $headers = []): void
    {
        http_response_code($code);

        $defaultHeaders = [
            "Access-Control-Allow-Headers" => "Content-Type",
            "Access-Control-Allow-Origin" => "*",
            "Content-Type" => "application/json; charset=utf-8"
        ];

        $mergedHeaders = array_merge($defaultHeaders, $headers);
        foreach ($mergedHeaders as $name => $value)
        {
            header($name . ": " . $value);
        }

        echo json_encode((object) [
            "data" => $this->data,
            "errors" => $this->errors,
            "status" => $this->status
        ]);

        exit;
    }

    /**
     * @param object $data
     */
    public function setData(object $data): void
    {
        $this->data = $data;
    }

    /**
     * @param object $errors
     */
    public function setErrors(object $errors): void
    {
        $this->errors = $errors;
    }

    /**
     * @param bool $status
     */
    public function setStatus(bool $status): void
    {
        $this->status = $status;
    }
}