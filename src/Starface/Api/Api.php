<?php

namespace Starface\Api;

use Starface\StarFace;
use fXmlRpc\ClientInterface;
use Starface\Exception\NotLoggedInException;

class Api
{
    private ClientInterface $client;

    private StarFace $starFace;

    private string $apiVersion;

    private string $defaultApiVersion = 'v22';

    public function __construct(
        ClientInterface $client,
        StarFace $starFace,
        string $apiVersion = 'v30'
    ) {
        $this->client = $client;
        $this->starFace = $starFace;
        $this->apiVersion = $apiVersion;
    }

    /**
     * This is the main request function to use!!
     * the others are deprecated
     * @param $method
     * @param array $params
     * @param bool $loginRequired
     * @throws NotLoggedInException
     * @return mixed|null|string
     */
    protected function rpcCall($method, $params = [], $loginRequired = true)
    {
        if ($loginRequired && !$this->starFace->isLoggedIn()) {
            throw new NotLoggedInException();
        }

        $normalizedMethod = $this->getNormalizedMethod($method);

        $response = $this->client->call($normalizedMethod, $params);

        $this->starFace->updateConnectionTime();

        return $response;
    }

    private function getNormalizedMethod($method)
    {
        if ($this->defaultApiVersion == $this->apiVersion) {
            return $method;
        }

        return str_replace(
            '.' . $this->defaultApiVersion . '.',
            '.' . $this->apiVersion . '.',
            $method
        );
    }
}
