<?php

namespace Ironex\Schema\Enum;

class CrudMethodEnum
{
    const CREATE = "create";
    const OPTIONS = "options";
    const READ = "read";
    const UPDATE = "update";
    const DELETE = "delete";

    const REQUEST_TO_CRUD_METHOD = [
        RequestMethodEnum::POST => self::CREATE,
        RequestMethodEnum::OPTIONS => self::OPTIONS,
        RequestMethodEnum::GET => self::READ,
        RequestMethodEnum::PUT => self::UPDATE,
        RequestMethodEnum::DELETE => self::DELETE
    ];
}