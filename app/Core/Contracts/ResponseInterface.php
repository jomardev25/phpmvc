<?php

namespace App\Core\Contracts;

use App\Core\Http\Response;

interface ResponseInterface
{
    public function view(string $view, array $data = [], int $status = Response::HTTP_OK, array $headers = []);

    public function json(array $data = [], int $status = Response::HTTP_OK, array $headers = []);

    public function download($file, $name = null, array $headers = [], string $disposition = "attachment");

}