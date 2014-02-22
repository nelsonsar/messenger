<?php

namespace Messenger\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Logout implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', function (Application $app) {
            $app['session']->clear();
            return $app->redirect('/home');
        });

        return $controllers;
    }
}
