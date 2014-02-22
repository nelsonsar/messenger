<?php

namespace Messenger\Test;

class RabbitMQServiceProviderStub
{
    private $methods = array('getAllQueuesFromVirtualHost');


    public function __call($name, array $arguments)
    {
        if (false !== array_search($name, $this->methods)) {
            return array();
        }

        throw new \Exception('Method does not exist');
    }
}
