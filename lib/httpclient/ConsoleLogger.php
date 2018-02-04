<?php

/**
 * Default {@link RequestLogger} used by {@link BasicHttpClient}.
 *
 * @author Arsene Tochemey GANDOTE
 */
class ConsoleLogger implements RequestLogger
{
    public function isLoggingEnabled()
    {
        return true;
    }

    public function log($mesg)
    {
        // $mesg."\n";
    }

    public function logRequest($httpRequest, $headers)
    {
        $this->log('=== HTTP Request ===');
        $this->logHeaders($headers);
        if ($httpRequest != null && $httpRequest instanceof HttpRequest) {
            $this->log('Request Method :'.$httpRequest->getHttpMethod());
            $this->log('Content : '.$httpRequest->getBody());
        }
    }

    public function logResponse($response)
    {
        if ($response != null && $response instanceof HttpResponse) {
            $this->log('=== HTTP Response ===');
            $this->log('Receive url :'.$response->getUrl());
            $this->log('Status : '.$response->getHttpStatus());
            //$response->getHeaders();
            //$response->getBody();
        }
    }

    private function logHeaders($headers)
    {
        if (isset($headers) && is_array($headers)) {
            foreach ($headers as $key => $value) {
                if (is_array($value)) {
                    // Here we ignore all headers with multiple values
                    continue;
                }
                $this->log($key.' : '.$value);
            }
        }
    }
}
