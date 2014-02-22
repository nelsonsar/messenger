<?php

namespace Messenger\Provider\Service;

use Messenger\Service\RabbitMQ\HttpClient;
use Silex\Application;
use Silex\ServiceProviderInterface;

class RabbitMQ implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['rabbitmq'] = $app->share(function(Application $app) {
            $client = $app['client'];
            $apiAddress = $app['api.address'];
            $user = $app['user'];
            $password = $app['password'];

            return new HttpClient($client, $apiAddress, $user, $password);
        });
    }

    public function boot(Application $app)
    {
    }
}
