<?php

namespace Messenger\Service\RabbitMQ;

use Guzzle\Http\Client;

class HttpClient
{
    private $apiAddress = '';
    private $client = null;
    private $user = '';
    private $password = '';

    public function __construct(Client $client, $apiAddress, $user, $password)
    {
        $this->apiAddress = $apiAddress;
        $this->client = $client;
        $this->password = $password;
        $this->user = $user;
    }

    public function getAllQueuesFromVirtualHost($virtualHost)
    {
        $result = array();

        if ('/' === $virtualHost) {
            $virtualHost = "%2f";
        }

        $this->client->setBaseUrl($this->apiAddress);
        $request = $this->client->get('/api/queues/' . $virtualHost)->setAuth($this->user, $this->password);
        $response = $request->send();
        $queues = $response->json();

        foreach ($queues as $queue) {
            $result[] = $queue['name'];
        }

        return $result;
    }
}
