<?php

namespace Messenger\Provider\Controller;

use Silex\Application;
use Silex\ControllerProviderInterface;

class Home implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];

        $controllers->get('/', function (Application $app) {
            $user = $app['session']->get('user');
            if (null === $user) {
                return $app->redirect('/');
            }

            return $app['twig']->render('user.html.twig', array(
                'title' => 'Dashboard',
                'user' => $user['login']
            ));
        });

        return $controllers;
    }
}
