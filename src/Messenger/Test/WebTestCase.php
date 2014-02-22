<?php

namespace Messenger\Test;

use Messenger\Application;
use Messenger\Test\RabbitMQServiceProviderStub;
use Silex\WebTestCase as SilexTestCase;

class WebTestCase extends SilexTestCase
{
    public function createApplication()
    {
        $applicationFactory = new Application;
        $app = $applicationFactory->getApplicationInstance();
        $app['session.test'] = true;
        $app['debug'] = true;
        $app['exception_handler']->disable();
        $app['rabbitmq'] = new RabbitMQServiceProviderStub;

        return $app;
    }
}
