<?php

namespace Ironex\Schema\Enum;

class RequestMethodEnum
{
    const GET = "GET";
    const DELETE = "DELETE";
    const OPTIONS = "OPTIONS";
    const POST = "POST";
    const PUT = "PUT";

    const CRUD_TO_REQUEST_METHOD = [
        CrudMethodEnum::READ => self::GET,
        CrudMethodEnum::DELETE => self::DELETE,
        CrudMethodEnum::OPTIONS => self::OPTIONS,
        CrudMethodEnum::CREATE => self::POST,
        CrudMethodEnum::UPDATE => self::PUT
    ];
}