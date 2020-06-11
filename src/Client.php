<?php

namespace Accolon\Request;

use Accolon\Request\Enums\ContentType;
use Accolon\Request\Enums\Methods;
use Accolon\Request\Request;
use Accolon\Request\Response;

class Client
{
    private string $baseUrl;
    private string $contentType;

    public function __construct(array $options = [])
    {
        $this->baseUrl = $options['baseUrl'] ?? "";
        $this->contentType = $options['contentType'] ?? ContentType::TEXT;
    }

    public function setContentType(string $type)
    {
        $this->contentType = $type;
    }

    public function get(string $url, $payload = null, array $headers = []): Response
    {
        if (is_array($payload) || is_object($payload)) {
            $payload = http_build_query($payload);
        }

        if (!$payload) {
            $payload = "";
        }

        $request = new Request(
            $this->baseUrl . $url . $payload,
            Methods::GET, [
            ...$headers,
            "Content-Type: {$this->contentType}"
        ]);

        $result = $request->run();

        $response = new Response($result, $request->getStatus());

        return $response;
    }

    public function post(string $url, $payload = "", array $headers = []): Response
    {
        $request = new Request(
            $this->baseUrl . $url,
            Methods::POST,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );

        if (ContentType::JSON) {
            $payload = json_encode($payload);
        }

        $result = $request->run($payload);

        $response = new Response($result, $request->getStatus());

        return $response;
    }
}