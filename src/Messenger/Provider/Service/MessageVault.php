<?php

namespace Messenger\Provider\Service;

use Messenger\Repository\Message;
use Silex\Application;
use Silex\ServiceProviderInterface;

class MessageVault implements ServiceProviderInterface
{
    public function register(Application $app)
    {
        $app['message_vault'] = $app->share(function() use ($app) {
            return new Message($app['db']);
        });
    }

    public function boot(Application $app)
    {
    }
}
