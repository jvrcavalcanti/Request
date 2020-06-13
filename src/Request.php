<?php

namespace Accolon\Request;

use Accolon\Request\Enums\Methods;

class Request
{
    private $curl;
    private string $url;
    private string $method;
    private array $headers;
    private int $status;

    public function __construct(
        string $url, string $method = Methods::GET, array $headers = []
    )
    {
        if (!extension_loaded("curl")) {
            throw new \RuntimeException("cUrl extension not loaded");
        }

        $this->curl = curl_init();

        $this->url = $url;
        $this->method = $method;
        $this->headers = $headers;  
    }

    public function getStatus()
    {
        return $this->status;
    }

    public function run (string $payload = "")
    {
        $options = [
            CURLOPT_URL => $this->url,
            CURLOPT_HTTPHEADER => $this->headers,
            CURLOPT_RETURNTRANSFER => true 
        ];

        if ($this->method === Methods::POST) {
            $options[CURLOPT_POST] = true;
        }

        if ($this->method !== Methods::GET && $this->method !== Methods::POST) {
            $options[CURLOPT_CUSTOMREQUEST] = $this->method;
            $options[CURLOPT_POSTFIELDS] = $payload;    
            $options[CURLOPT_HTTPHEADER] = [
                ...$options[CURLOPT_HTTPHEADER],
                "Content-Length: " . strlen($payload)
            ];
        }

        curl_setopt_array($this->curl, $options);

        $result = curl_exec($this->curl);

        $this->status = curl_getinfo($this->curl, CURLINFO_HTTP_CODE);

        curl_close($this->curl);

        return $result;
    }
}