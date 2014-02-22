<?php

namespace Messenger;

use Guzzle\Http\Client;
use Messenger\Provider\Controller\Conversation;
use Messenger\Provider\Controller\Home;
use Messenger\Provider\Controller\Login;
use Messenger\Provider\Controller\Logout;
use Messenger\Provider\Service\MessageVault;
use Messenger\Provider\Service\RabbitMQ;
use Silex\Application as SilexApplication;
use Silex\Provider\DoctrineServiceProvider;
use Silex\Provider\SessionServiceProvider;
use Silex\Provider\TwigServiceProvider;
use Symfony\Component\HttpFoundation\Response;

class Application
{
    public function getApplicationInstance()
    {
        $app = new SilexApplication();

        $app->register(new DoctrineServiceProvider, array(
            'db.options' => array(
                'driver'   => 'pdo_sqlite',
                'path'     => __DIR__.'/../../resources/messenger.db',
            ),
        ));

        $app->register(new SessionServiceProvider);

        $app->register(new TwigServiceProvider(), array(
            'twig.path' => __DIR__ . '/../../web/templates',
        ));

        $app->register(new RabbitMQ, array(
            'client' => new Client,
            'api.address' => 'http://69.195.223.58:15672',
            'user' => 'dafitconf',
            'password' => 'dafiti'
        ));

        $app->register(new MessageVault);

        //Ok, this is awful...
        $app->error(function (\Exception $exception, $code) use ($app) {
            $content = $app['twig']->render('error.html.twig', array('message' => $exception->getMessage()));
            return new Response($content, $code);
        });

        $app->mount('/login', new Login);
        $app->mount('/home', new Home);
        $app->mount('/conversation', new Conversation);
        $app->mount('/logout', new Logout);

        $app->get('/', function () use ($app) {
            return $app->redirect('/login');
        });

        return $app;
    }
}
