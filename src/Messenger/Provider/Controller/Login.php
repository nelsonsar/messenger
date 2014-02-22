<?php

namespace Messenger\Provider\Controller;

use Messenger\Validation\Login as LoginValidator;
use Silex\Application;
use Silex\ControllerProviderInterface;
use Symfony\Component\HttpFoundation\Request;

class Login implements ControllerProviderInterface
{
    public function connect(Application $app)
    {
        $controllers = $app['controllers_factory'];
        $controllers->get('/', function (Application $app) {
            $user = $app['session']->get('user');
            if (null !== $user) {
                return $app->redirect('/home');
            }

            return $app['twig']->render('signin.html.twig');
        });

        $controllers->post('/', function (Request $request) use ($app) {
            $login = $app->escape($request->get('login'));
            $validator = new LoginValidator;
            if (false === $validator->validate($login)) {
                throw new \DomainException('User login can only have letters and "_", "." characters', 400);
            }

            $users = $app['rabbitmq']->getAllQueuesFromVirtualHost('/');

            if (false !== array_search($login, $users)) {
                throw new \DomainException('Login already in use...', 400);
            }

            $app['session']->set('user', array('login' => $login));

            return $app->redirect('/home');
        });

        return $controllers;
    }
}
