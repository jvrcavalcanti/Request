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

    private function request(string $url, string $method, $payload, array $headers): Response
    {
        $request = new Request(
            $this->baseUrl . $url,
            $method,
            $headers
        );

        if (!$payload) {
            $result = $request->run();

            return new Response(
                $result,
                $request->getStatus()
            );
        }

        if ($this->contentType === ContentType::JSON) {
            $payload = json_encode($payload);
        }

        $result = $request->run();

        return new Response(
            $result,
            $request->getStatus()
        );
    }

    public function get(string $url, $payload = null, array $headers = [])
    {
        if (is_array($payload) || is_object($payload)) {
            $payload = http_build_query($payload);
        }

        if (!$payload) {
            $payload = "";
        }

        return $this->request(
            $url . $payload,
            Methods::GET,
            $payload,
            $headers
        );
    }

    public function post(string $url, $payload = null, array $headers = [])
    {
        return $this->request(
            $url,
            Methods::POST,
            $payload,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );
    }

    public function put(string $url, $payload = null, array $headers = [])
    {
        return $this->request(
            $url,
            Methods::PUT,
            $payload,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );
    }

    public function patch(string $url, $payload = null, array $headers = [])
    {
        return $this->request(
            $url,
            Methods::PATCH,
            $payload,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );
    }

    public function delete(string $url, $payload = null, array $headers = [])
    {
        return $this->request(
            $url,
            Methods::DELETE,
            $payload,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );
    }

    public function options(string $url, $payload = null, array $headers = [])
    {
        return $this->request(
            $url,
            Methods::OPTIONS,
            $payload,
            [
                ...$headers,
                "Content-Type: {$this->contentType}"
            ]
        );
    }
}
