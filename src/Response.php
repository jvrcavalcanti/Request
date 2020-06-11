<?php

namespace Accolon\Request;

class Response
{
    private $body;
    private int $status;

    public function __construct($body, int $status)
    {
        $this->body = $body;
        $this->status = $status;
    }

    public function json()
    {
        return json_decode($this->body);
    }

    public function text()
    {
        return $this->body;
    }

    public function ok(): bool
    {
        return $this->status >= 300 ? false : true;
    }
}