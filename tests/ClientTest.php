<?php

use Accolon\Request\Client;
use Accolon\Request\Enums\ContentType;
use PHPUnit\Framework\TestCase;

class ClientTest extends TestCase
{
    private Client $client;

    public function setUp(): void
    {
        $this->client = new Client([
            "baseUrl" => "https://my-json-server.typicode.com/typicode/demo/",
            "contentType" => ContentType::JSON
        ]);
    }

    public function testGet()
    {
        $response = $this->client->get("posts");

        $this->assertEquals(
            sizeof($response->json()),
            3
        );
    }

    public function testPost()
    {
        $response = $this->client->post(
            "posts", [
            "title" => "foo",
            "body" => "bar",
            "userId" => 1
        ]);

        $this->assertEquals(
            $response->json()->id,
            4
        );
    }
}