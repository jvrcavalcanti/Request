<?php

use Accolon\Request\Enums\Methods;
use Accolon\Request\Request;
use PHPUnit\Framework\TestCase;

class RequestTest extends TestCase
{
    public function testBasicGet()
    {
        $request = new Request(
            "https://my-json-server.typicode.com/typicode/demo/posts"
        );

        $result = $request->run(json_encode([
            "title" => "foo",
            "body" => "bar",
            "userId" => 1
        ]));

        $this->assertEquals(
            sizeof(json_decode($result)),
            3
        );
    }

    public function testBasicPost()
    {
        $request = new Request(
            "https://my-json-server.typicode.com/typicode/demo/posts",
            Methods::POST,
            ["Content-Type: application/json"]
        );

        $result = $request->run(json_encode([
            "title" => "foo",
            "body" => "bar",
            "userId" => 1
        ]));

        $this->assertEquals(
            json_decode($result)->id,
            4
        );
    }
}