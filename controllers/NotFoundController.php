<?php

namespace Controllers;

use Controllers\BaseController;

class NotFoundController extends BaseController
{
    public function __construct()
    {
        http_response_code(404);
    }

    public function index(): string
    {
        return $this->getView(['message' => '404']);
    }
}