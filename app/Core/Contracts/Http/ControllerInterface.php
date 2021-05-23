<?php

namespace App\Core\Contracts\Http;

use App\Core\Http\Response;

interface ControllerInterface
{
    public function view(string $view, array $data = [], int $status = Response::HTTP_OK, array $headers = []);

    public function json(array $data = [], int $status = Response::HTTP_OK, array $headers = []);

    public function download($file, $name = null, array $headers = [], string $disposition = "attachment");

}